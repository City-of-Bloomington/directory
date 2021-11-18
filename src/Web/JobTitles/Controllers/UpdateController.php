<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\JobTitles\Controllers;

use Web\Controller;
use Web\View;
use Web\JobTitles\Views\UpdateView;

use Domain\JobTitles\Actions\Update\Request;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (isset($_POST['id'])) {
            $update = $this->di->get('Domain\JobTitles\Actions\Update\Command');
            $req    = new Request($_POST);
            $res    = $update($req);
            if (!$res->errors) {
                header("Location: ".View::generateUrl('titles.index'));
                exit();
            }
            $_SESSION['errorMessages'] = $res->errors;
        }

        try {
            $info = $this->di->get('Domain\JobTitles\Actions\Info\Command');
            $res  = $info((int)$params['id']);
            if ($res->errors) {
                $_SESSION['errorMessages'] = $res->errors;
            }
            $req  = new Request((array)$res->title);

            return new UpdateView($req);
        }
        catch (\Exception $e) {
            $_SESSION['errorMessages'] = [$e];
            return new \Web\Views\NotFoundView();
        }
    }
}
