<?php
/**
 * Easter Egg to show a bunch of monkeys
 *
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Departments\Controllers;

use Web\Controller;
use Web\View;

class MonkeysController extends Controller
{
    public function __invoke(array $params): View
    {
        return new MonkeyView();
    }
}
class MonkeyView extends View
{
    public function render(): string
    {
        return $this->twig->render('html/furiousGeorge.twig', []);
    }
}
