<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\DepartmentGateway;
use Application\Models\HRGateway;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $nonEmployees = [];
        $people = DepartmentGateway::search(['employeeNum'=>null]);
        foreach ($people as $p) {
            if (!$p->employeeNum) {
                $nonEmployees[] = [
                    'username'   => $p->username,
                    'name'       => $p->getFullname(),
                    'title'      => $p->title,
                    'department' => $p->department
                ];
            }
        }

        $this->template->blocks[] = new Block('admin/people.inc', [
            'people' => $nonEmployees,
            'title'  => $this->template->_('notInNewWorld')
        ]);

        $missingPeople = [];
        $employees = HRGateway::getEmployees();
        foreach ($employees as $e) {
            $person = DepartmentGateway::getPerson($e['employeeNumber']);
            if (!$person) {
                $missingPeople[] = [
                    'employeeNum' => $e['employeeNumber'],
                    'name'        => $e['name'],
                    'title'       => $e['title'],
                    'department'  => $e['department']
                ];
            }
        }
        $this->template->blocks[] = new Block('admin/people.inc', [
            'people' => $missingPeople,
            'title'  => $this->template->_('notInActiveDirectory')
        ]);
    }
}