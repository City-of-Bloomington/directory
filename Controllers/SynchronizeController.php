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
        $people = DepartmentGateway::search(['employeeNum'=>null]);
        foreach ($people as $p) {
            if (!$p->employeeNum) {
                $adRecords[] = [
                    DirectoryAttributes::USERNAME   => $p->username,
                    DirectoryAttributes::NAME       => $p->getFullname(),
                    DirectoryAttributes::TITLE      => $p->title,
                    DirectoryAttributes::DEPARTMENT => $p->department
                ];
            }
        }

        $hrRecords = [];
        $employees = HRGateway::getEmployees();
        foreach ($employees as $e) {
            $person = DepartmentGateway::getPerson($e[DirectoryAttributes::EMPLOYEENUM]);
            if (!$person) {
                // The HRGateway should be using SQL to select the fields using
                // our internal fieldnames
                $hrRecords[] = [
                    DirectoryAttributes::EMPLOYEENUM => $e[DirectoryAttributes::EMPLOYEENUM],
                    DirectoryAttributes::NAME        => $e[DirectoryAttributes::NAME],
                    DirectoryAttributes::TITLE       => $e[DirectoryAttributes::TITLE],
                    DirectoryAttributes::DEPARTMENT  => $e[DirectoryAttributes::DEPARTMENT]
                ];
            }
        }

        $this->template->blocks[] = new Block('synchronize/synchronizeForm.inc', [
            'adRecords' => $adRecords,
            'hrRecords' => $hrRecords
        ]);
    }

    public function compare()
    {
        if (!empty($_GET[DirectoryAttributes::USERNAME])) {
            $person = DepartmentGateway::getPerson($_GET[DirectoryAttributes::USERNAME]);
            if (!$person) {
                $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
            }
        }
        else {  $_SESSION['errorMessages'][] = new \Exception('synchronize/missingPerson'); }

        if (!empty($_GET[DirectoryAttributes::EMPLOYEENUM])) {
            $employee = HRGateway::getEmployee($_GET[DirectoryAttributes::EMPLOYEENUM]);
            if (!$employee) {
                $_SESSION['errorMessages'][] = new \Exception('people/unknownPerson');
            }
        }
        else {  $_SESSION['errorMessages'][] = new \Exception('synchronize/missingEmployee'); }

        if (isset($person) && isset($employee)) {
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
