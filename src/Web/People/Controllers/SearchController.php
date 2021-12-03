<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\View;

use Web\People\Views\SearchView;
use Domain\People\Actions\Search\Response;

class SearchController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_GET['query'])) {
            if ($_GET['query'] == 'monkeys') {
                header('Location: '.View::generateUrl('departments.monkeys'));
                exit();
            }

            $search = $this->di->get('Domain\People\Actions\Search\Command');
            $res    = $search($_GET['query']);
            if (count($res->people) == 1) {
                header('Location: '.View::generateUrl('people.view', ['username'=>$res->people[0]->username ]));
                exit();
            }
            return new SearchView($res, $_GET['query']);
        }

        return new SearchView(new Response());
    }
}
