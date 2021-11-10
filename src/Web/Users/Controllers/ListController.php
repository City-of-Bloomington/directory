<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Controllers;

use Domain\Users\Actions\Search\Request;
use Web\Users\Views\SearchView;
use Web\Controller;
use Web\View;


class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        global $ACL;
        $search   = $this->di->get('Domain\Users\Actions\Search\Command');
        $auth     = $this->di->get('Web\Authentication\AuthenticationService');
		$page     =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $request  = new Request($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = $search($request);

        return new SearchView($request,
                              $response,
                              parent::ITEMS_PER_PAGE,
                              $page,
                              $ACL->getRoles(),
                              $auth->getAuthenticationMethods());
    }
}
