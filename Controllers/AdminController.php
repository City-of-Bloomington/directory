<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\DepartmentGateway;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $people = DepartmentGateway::search(['employeeNum'=>null]);
        $this->template->blocks[] = new Block('people/list.inc', [
            'people' => $people,
            'title'  => $this->template->_('notInNewWorld')
        ]);
    }
}