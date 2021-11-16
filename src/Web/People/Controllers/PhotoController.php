<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;

class PhotoController extends Controller
{
    public function __invoke(array $params)
    {
        $info = $this->di->get('Domain\People\Actions\Info\Command');
        $res  = $info($params['username']);
        if ($res->person) {
            $file = SITE_HOME."/photos/{$res->person->username}.jpg";
            if (is_file($file)) {
                header('Content-type: image/jpeg');
                echo file_get_contents($file);
                exit();
            }
        }

        header('HTTP/1.1 404 Not Found', true, 404);
        exit();
    }
}
