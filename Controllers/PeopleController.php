<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\DepartmentGateway;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class PeopleController extends Controller
{
    public function index()
    {
    }

    public function view()
    {
        $gateway = new DepartmentGateway();
        $person = $gateway->getPerson($_GET['username']);

        $this->template->blocks[] = new Block('people/info.inc', ['person'=>$person]);
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
            $gateway = new DepartmentGateway();
            $people = $gateway->search($_GET);
            if (count($people) == 1) {
                $username = $people[0]->getUsername();
                header('Location: '.BASE_URL."/people/view?username=$username");
                exit();
            }
            else {
                $this->template->blocks[] = new Block('people/list.inc', ['people'=>$people]);
            }
        }
        $this->template->blocks[] = new Block('people/searchForm.inc');
    }

    public function photo()
    {
        $gateway = new DepartmentGateway();
        $person = $gateway->getPerson($_GET['username']);
        header('Content-type: image/jpeg');
        echo $gateway->getPhoto($_GET['username']);
        exit();
    }

    public function uploadPhoto()
    {
        if (!empty($_POST['username'])) {
            $gateway = new DepartmentGateway();
            $person = $gateway->getPerson($_POST['username']);
            if ($person) {
                if (isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
                    try {
                        $person->setPhoto($_FILES['photo']);
                    }
                    catch (\Exception $e) {
                        $_SESSION['errorMessages'][] = $e;
                    }
                }
                header('Location: '.BASE_URL."/people/view?username={$person->getUsername()}");
                exit();
            }
        }
        $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
        header('Location: '.BASE_URL);
        exit();
    }
}
