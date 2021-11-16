<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\View;
use Domain\People\Actions\SavePhoto\Request;

class UploadPhotoController extends Controller
{
    public function __invoke(array $params)
    {
        if (!empty($_POST['username'])
            && isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {

            $save = $this->di->get('Domain\People\Actions\SavePhoto\Command');
            $req  = new Request($_POST['username'], $_FILES['photo']['tmp_name']);
            $res  = $save($req);
            if ($res->errors) {
                $_SESSION['errorMessages'] = $res->errors;
            }
            header('Location: '.View::generateUrl('people.view', ['username'=>$_POST['username']]));
            exit();
        }
        header('HTTP/1.1 404 Not Found', true, 404);
        exit();
    }
}
