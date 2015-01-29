<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\EmergencyContactsTable;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class EmergencyContactsController extends Controller
{
    public function index()
    {
        $pagination = $this->template->outputFormat == 'html';

        $table = new EmergencyContactsTable();
        $list = $table->find(null, null, $pagination);

        if ($pagination) {
            $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
            $list->setCurrentPageNumber($page);
            $list->setItemCountPerPage(20);

        }

        $this->template->blocks[] = new Block('emergencyContacts/list.inc', ['contacts'=>$list]);
        if ($pagination) {
            $this->template->blocks[] = new Block('pageNavigation.inc', ['paginator'=>$list]);
        }
    }
}