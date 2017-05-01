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

class DepartmentsController extends Controller
{
    public function index()
    {
        $this->template->blocks[] = new Block('departments/list.inc');
    }

    public function view()
    {
        try {
            $department = DepartmentGateway::getDepartment($_GET['dn']);
            $this->template->blocks[] = new Block('departments/info.inc', ['department'=>$department]);
        }
        catch (\Exception $e) {
            if ($_GET['dn'] == 'OU=monkeys,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov') {
                $this->template->blocks[] = new Block('furiousGeorge.inc');
            }
            else {
                if ($e->getMessage() == DepartmentGateway::LDAP_CONNECTION_FAILED) {
                    header('HTTP/1.1 504 Gateway Timeout', true, 504);
                }
                else {
                    header('HTTP/1.1 404 Not Found', true, 404);
                }
                $_SESSION['errorMessages'][] = $e;
            }
        }
    }

    public function numbers()
    {
        $this->template->blocks[] = new Block('departments/numbers.inc');
    }
}
