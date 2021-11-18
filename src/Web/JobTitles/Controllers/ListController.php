<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\JobTitles\Controllers;

use Web\Controller;
use Web\View;
use Web\JobTitles\Views\ListView;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        $find = $this->di->get('Domain\JobTitles\Actions\Find\Command');
        $res  = $find();
        return new ListView($res->titles);
    }
}
