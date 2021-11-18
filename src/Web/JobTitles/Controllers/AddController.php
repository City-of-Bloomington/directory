<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\JobTitles\Controllers;

use Domain\JobTitles\Actions\Add\Request;

use Web\Controller;
use Web\View;
use Web\JobTitles\Views\AddView;

class AddController extends Controller
{
    public function __invoke(array $params): View
    {
        if (isset($_POST['code'])) {
            $add = $this->di->get('Domain\JobTitles\Actions\Add\Command');
            $req = new Request((array)$_POST);
            $res = $add($req);
            if (!$res->errors) {
                header('Location: '.View::generateUrl('title.index'));
                exit();
            }
            $_SESSION['errorMessages'] = $res->errors;
        }

        if (!isset($req)) { $req = new Request(); }

        return new AddView($req);
    }
}
