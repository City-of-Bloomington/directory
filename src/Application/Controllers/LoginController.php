<?php
/**
 * @copyright 2012-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Employee;
use Application\Models\User;
use Blossom\Classes\Controller;
use Blossom\Classes\Template;
use Blossom\Classes\Block;

class LoginController extends Controller
{
	private $return_url;

	public function __construct(Template $template)
	{
		parent::__construct($template);
		$this->return_url = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;
	}

	/**
	 * Attempts to authenticate users via CAS
	 */
	public function index()
	{
		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		if (!defined('CAS')) {
			header('Location: '.BASE_URL.'/login/login?return_url='.$this->return_url);
			exit();
		}

		require_once CAS.'/CAS.php';
		\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().
		$username = \phpCAS::getUser();

		// They may be authenticated according to CAS,
		// but that doesn't mean they have person record
		// and even if they have a person record, they may not
		// have a user account for that person record.
		try {
			$_SESSION['USER'] = new User($username);
			header("Location: {$this->return_url}");
			exit();
		}
		catch (\Exception $e) {
            // If they have a CAS authentication, go ahead and log them into the
            // site as Staff.  We don't need to save the user accounts in the database
            try {
                $employee = new Employee($username);

                $_SESSION['USER'] = new User();
                $_SESSION['USER']->setUsername($username);
                $_SESSION['USER']->setRole('Staff');
                $_SESSION['USER']->setAuthenticationMethod('Employee');
                $_SESSION['USER']->populateFromExternalIdentity($employee);
                header("Location: {$this->return_url}");
                exit();
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'][] = $e;
            }
		}

		$this->template->blocks[] = new Block('loginForm.inc', ['return_url'=>$this->return_url]);
	}

	/**
	 * Attempts to authenticate users based on AuthenticationMethod
	 */
	public function login()
	{
		if (isset($_POST['username'])) {
			try {
				$person = new User($_POST['username']);
				if ($person->authenticate($_POST['password'])) {
					$_SESSION['USER'] = $person;
					header('Location: '.$this->return_url);
					exit();
				}
				else {
					throw new \Exception('invalidLogin');
				}
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		$this->template->blocks[] = new Block('loginForm.inc',array('return_url'=>$this->return_url));
	}

	public function logout()
	{
		session_destroy();
		header('Location: '.$this->return_url);
		exit();
	}
}
