<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\View;
use Web\People\Views\InfoView;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        $info = $this->di->get('Domain\People\Actions\Info\Command');
        $res  = $info($params['username']);
        if ($res->person) {
            if (View::isAllowed('emergencyContacts', 'update')) {
                $load = $this->di->get('Domain\EmergencyContacts\Actions\Load\Command');
                $er   = $load($params['username']);
                if ($er->contact) {
                    return new InfoView($res->person, $er->contact);
                }
            }

            return new InfoView($res->person);
        }
        else {
            $_SESSION['errorMessages'] = $res->errors;
            return new \Web\Views\NotFoundView();
        }
    }
}
