<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\DepartmentGateway;
use Application\Models\User;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;
use Blossom\Classes\Template;

class PeopleController extends Controller
{
    private function handle404()
    {
        header('HTTP/1.0 404 Not Found');
        $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
    }


    public function index()
    {
    }

    public function view()
    {
        $person = DepartmentGateway::getPerson($_GET['username']);
        if (!$person) { $this->handle404(); return; }

        $this->template->blocks[] = new Block('people/info.inc', ['person'=>$person]);

        if ($this->template->outputFormat == 'html'
            && User::isAllowed('people', 'updateEmergencyContacts')) {

            $this->template->blocks[] = new Block('emergencyContacts/info.inc', ['person'=>$person]);
        }
    }

    public function update()
    {
        $person = DepartmentGateway::getPerson($_REQUEST['username']);
        if (!$person) { $this->handle404(); return; }

        if (isset($_POST['username'])) {
            try {
                $person->handleUpdate($_POST);
                $person->save();
                header('Location: '.BASE_URL.'/people/view?username='.$person->username);
                exit();
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'][] = $e;
            }
        }

        $this->template->blocks[] = new Block('people/updateForm.inc', ['person'=>$person]);
    }

    public function search()
    {
        if (!empty($_GET['lastname']) || !empty($_GET['firstname']) || !empty($_GET['extension'])
            || !empty($_GET['query'])) {
            // Begin easter egg
            if (!empty($_GET['query']) && $_GET['query']=='monkeys') {
                header('Location: '.BASE_URL.'/index/monkeys');
                exit();
            }
            // End easter egg
            $people = DepartmentGateway::search($_GET);
            if (count($people) == 1 && $this->template->outputFormat == 'html') {
                $username = $people[0]->username;
                header('Location: '.BASE_URL."/people/view?username=$username");
                exit();
            }
            else {
                $this->template->blocks[] = new Block('people/searchResults.inc', ['people'=>$people]);
            }
        }
        if ($this->template->outputFormat == 'html') {
            $this->template->blocks[] = new Block('people/searchForm.inc');
        }
    }

    public function photo()
    {
        $person = DepartmentGateway::getPerson($_GET['username']);
        if (!$person) { $this->handle404(); return; }

        header('Content-type: image/jpeg');
        echo DepartmentGateway::getPhoto($_GET['username']);
        exit();
    }

    public function uploadPhoto()
    {
        if (!empty($_POST['username'])) {
            $person = DepartmentGateway::getPerson($_POST['username']);
            if ($person) {
                if (isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
                    try {
                        $person->setPhoto($_FILES['photo']);
                    }
                    catch (\Exception $e) {
                        $_SESSION['errorMessages'][] = $e;
                    }
                }
                header('Location: '.BASE_URL."/people/view?username={$person->username}");
                exit();
            }
        }
        $this->handle404();
        exit();
    }

    public function updateEmergencyContacts()
    {
        $person  = DepartmentGateway::getPerson($_REQUEST['username']);
        if (!$person) { $this->handle404(); return; }

        $contact = $person->getEmergencyContacts();

        if (!empty($_POST['username'])) {
            try {
                $contact->handleUpdate($_POST);
                $contact->save();
                header('Location: '.BASE_URI.'/people/view?username='.$person->username);
                exit();
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'][] = $e;
            }
        }

        $this->template->blocks[] = new Block('emergencyContacts/updateForm.inc', ['contact'=> $contact]);
    }
}
