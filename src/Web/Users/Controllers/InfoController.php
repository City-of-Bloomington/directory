<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Controllers;

use Web\Controller;
use Web\View;
use Web\Users\Views\InfoView;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Users\Actions\Info\Command');
            $res  = $info((int)$_REQUEST['id']);
            if ($res->user) {
                return new InfoView($res->user);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Web\Views\NotFoundView();
    }
}
