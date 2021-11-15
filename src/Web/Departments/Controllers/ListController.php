<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Departments\Controllers;

use Web\Controller;
use Web\View;

use Web\Departments\Views\ListView;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        $search = $this->di->get('Domain\Departments\Actions\Search\Command');
        $res    = $search();
        return new ListView($res->departments);
    }
}
