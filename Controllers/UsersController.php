<?php
/**
 * @copyright 2012-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;
use Application\Models\User;
use Application\Models\UsersTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;
use Blossom\Classes\Database;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

class UsersController extends Controller
{
    private function loadUser($id)
    {
        try {
            return new User($id);
        }
        catch (\Exception $e) {
            $_SESSION['errorMessages'][] = $e;
            header('Location: '.BASE_URL.'/users');
            exit();
        }
    }

	public function index()
	{
		$table = new UsersTable();
		$users = $table->find(null, null, true);

		$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$users->setCurrentPageNumber($page);
		$users->setItemCountPerPage(20);

		$this->template->blocks[] = new Block('users/list.inc',         ['users'=>$users]);
		$this->template->blocks[] = new Block('pageNavigation.inc', ['paginator'=>$users]);
	}

	public function update()
	{
		$user = isset($_REQUEST['user_id'])
            ? $this->loadUser($_REQUEST['user_id'])
            : new User();

		if (isset($_POST['username'])) {
			try {
				$user->handleUpdate($_POST);
				$user->save();
				header('Location: '.BASE_URL.'/users');
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		$this->template->blocks[] = new Block('users/updateForm.inc', ['user'=>$user]);
	}

	public function delete()
	{
        $user = $this->loadUser($_REQUEST['user_id']);
        $user->delete();

		header('Location: '.BASE_URL.'/users');
		exit();
	}
}
