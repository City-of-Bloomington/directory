<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\View;

use Domain\People\Actions\Update\Request;
use Web\People\Views\UpdateView;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (isset($_POST['username'])) {
            $update = $this->di->get('Domain\People\Actions\Update\Command');
            $req    = new Request($_POST);
            $res    = $update($req);
            if (!$res->errors) {
                header('Location: '.View::generateUrl('people.view', ['username'=>$res->person->username]));
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        
        $info = $this->di->get('Domain\People\Actions\Info\Command');
        $res  = $info($params['username']);
        if ($res->errors) {
            return new \Views\NotFoundView();
        }
        
        $req  = new Request((array)$res->person);
        
        return new UpdateView($req, $res->person);
    }
}
