<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Departments\Controllers;

use Web\Controller;
use Web\View;
use Web\Departments\Views\InfoView;

use Domain\Departments\Actions\Info\Request;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        $req  = new Request($params['path'] ?? null,
                            isset($_GET['promoted']) ? (bool)$_GET['promoted'] : false);
        $info = $this->di->get('Domain\Departments\Actions\Info\Command');
        $res  = $info($req);
        return new InfoView($res->department);
    }
}
