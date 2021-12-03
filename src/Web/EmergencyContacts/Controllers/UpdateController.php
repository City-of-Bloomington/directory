<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\EmergencyContacts\Controllers;

use Web\Controller;
use Web\View;

use Domain\EmergencyContacts\Contact;
use Domain\EmergencyContacts\Actions\Update\Request;
use Web\EmergencyContacts\Views\UpdateView;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        $load    = $this->di->get('Domain\EmergencyContacts\Actions\Load\Command');
        $update  = $this->di->get('Domain\EmergencyContacts\Actions\Update\Command');
        $info    = $this->di->get('Domain\People\Actions\Info\Command');

        $res     = $load($params['username']);
        if ($res->contact) {
            $contact = $res->contact;
        }
        else {
            $res = $info($params['username']);
            if ($res->errors) {
                $_SESSION['errorMessages'] = $res->errors;
                return \Web\Views\NotFoundView();
            }

            $contact = new Contact([
                'username'  => $res->person->username,
                'firstname' => $res->person->firstname,
                'lastname'  => $res->person->lastname
            ]);
        }

        if (isset($_POST['username'])) {
            $req = new Request($_POST);
            foreach ($req as $k=>$v) { $contact->$k = $v; }
            $res = $update($contact);
            if (!$res->errors) {
                header('Location: '.View::generateUrl('people.view', ['username'=>$contact->username]));
                exit();
            }

            $_SESSION['errorMessages'] = $res->errors;
        }
        else {
            $req = new Request((array)$contact);
        }

        return new UpdateView($req);
    }

    private function validate(Contact $contact): array
    {
        $errors = [];
        if (!$contact->username ) { $errors[] = 'missingUsername';  }
        if (!$contact->firstname) { $errors[] = 'missingFirstname'; }
        if (!$contact->lastname ) { $errors[] = 'missingLastname';  }
    }
}
