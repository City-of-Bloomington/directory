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

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        $path = $params['path'] ?? null;
        $info = $this->di->get('Domain\Departments\Actions\Info\Command');
        $res  = $info($path);
        return new InfoView($res->department);
    }
}
