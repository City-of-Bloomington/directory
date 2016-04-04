<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\DirectoryAttributes;
use Application\Models\DepartmentGateway;
use Application\Models\HRGateway;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class SynchronizeController extends Controller
{
    public function index()
    {
        $adRecords = [];
        $people = DepartmentGateway::search(['employeeNum'=>null, 'non-payroll'=>null]);
        foreach ($people as $p) {
            if (!$p->employeeNum) {
                $adRecords[] = [
                    DirectoryAttributes::USERNAME   => $p->username,
                    DirectoryAttributes::FIRSTNAME  => $p->firstname,
                    DirectoryAttributes::LASTNAME   => $p->lastname,
                    DirectoryAttributes::TITLE      => $p->title,
                    DirectoryAttributes::DEPARTMENT => $p->department
                ];
            }
        }

        #$hrRecords = [];
        $hrRecords = HRGateway::getEmployeesWithoutAccounts();
        /**
         * Does a lookup in ActiveDirctory to see if we have someone with that employeeNum
         * This shouldn't be needed anymore, since we've populated the ActiveDirctory usernames
         * in New World.
         */
        /*
        foreach ($employees as $e) {
            $person = DepartmentGateway::getPerson($e[DirectoryAttributes::EMPLOYEENUM]);
            if (!$person) {
                // The HRGateway should be using SQL to select the fields using
                // our internal fieldnames
                $hrRecords[] = $e;
            }
        }
        */

        $this->template->blocks[] = new Block('synchronize/synchronizeForm.inc', [
            'adRecords' => $adRecords,
            'hrRecords' => $hrRecords
        ]);
    }

    public function compare()
    {
        if (!empty($_REQUEST['username'])) {
            $person = DepartmentGateway::getPerson($_REQUEST['username']);
            if (!$person) {
                $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
            }
        }
        else {  $_SESSION['errorMessages'][] = new \Exception('synchronize/missingPerson'); }

        if (!empty($_REQUEST['employeeNum'])) {
            $employee = HRGateway::getEmployee($_REQUEST['employeeNum']);
            if (!$employee) {
                $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
            }
        }
        else {  $_SESSION['errorMessages'][] = new \Exception('synchronize/missingEmployee'); }

        if (isset($person) && isset($employee)) {
            if (isset($_POST['username'])) {
                $person->employeeNum = $employee['employeeNum'];
                $person->save();

                $employee['username']  = $person->username;
                HRGateway::saveEmployeeUsername($employee);
                header('Location: '.BASE_URL.'/people/view?username='.$person->username);
                exit();
            }

            $this->template->blocks[] = new Block('synchronize/compareForm.inc', [
                'adRecord' => $person,
                'hrRecord' => $employee
            ]);
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
        }
    }
}
