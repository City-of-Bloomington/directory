<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication;

use Aura\Di\Container;

use Web\Controller;
use Web\Template;
use Web\View;

class LoginController extends Controller
{
	private $return_url;
	private $auth;

	public function __construct(Container $container)
	{
        parent::__construct($container);
        $this->auth = $this->di->get('Web\Authentication\AuthenticationService');
	}

    /**
     * Try to do CAS authentication
     */
    public function __invoke(array $params): View
    {
		$_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;

		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		if (!defined('CAS_SERVER')) {
			header('Location: '.View::generateUrl('login.login').'?return_url='.parent::generateUrl('login.login'));
			exit();
		}

		\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().

		// They may be authenticated according to CAS,
		// but that doesn't mean they have person record
		// and even if they have a person record, they may not
		// have a user account for that person record.
		try { $user = $this->auth->identify(\phpCAS::getUser()); }
		catch (\Exception $e) {
            $_SESSION['errorMessages'][] = $e;
            return new \Web\Views\ForbiddenView();
        }

		if (isset($user) && $user) { $_SESSION['USER'] = $user; }
		else {
            $_SESSION['errorMessages'][] = 'users/unknownUser';
            return new \Web\Views\ForbiddenView();
        }

        $return_url = $_SESSION['return_url'];
        unset($_SESSION['return_url']);
        header("Location: $return_url");
        exit();
    }

    public function localAuth(array $params): View
    {
        if (isset($_POST['username'])) {

        }
		return new LoginView(['return_url'=>$this->return_url]);
    }

    public static function password_hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
