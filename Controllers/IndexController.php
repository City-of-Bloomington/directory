<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index()
    {
        $this->template->blocks[] = new Block('departments/list.inc');
	}

	public function monkeys()
	{
        $this->template->blocks[] = new Block('furiousGeorge.inc');
	}
}
