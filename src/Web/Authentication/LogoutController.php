<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication;

use Web\Controller;
use Web\Template;
use Web\View;

class LogoutController extends Controller
{
    public function __invoke()
    {
		session_destroy();
		if (defined('CAS_SERVER')) {
            \phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
            \phpCAS::logout();
        }
		header('Location: '.BASE_URL);
		exit();
    }
}
