<?php
/**
 * @copyright 2019-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Web\View;

use Domain\Users\Actions\Search\Request;
use Domain\Users\Actions\Search\Response;

class SearchView extends View
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage,
                                array    $roles,
                                array    $authentication_methods)
    {
        if ($response->errors) { $_SESSION['errorMessages'] = $response->errors; }
        
        parent::__construct();

        $this->vars = array_merge((array)$request, [
            'users'                  => $response->users,
            'total'                  => $response->total,
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods,
        ]);

        $fields = array_keys((array)$request);
        foreach ($_REQUEST as $k=>$v) {
            if (!in_array($k, $fields)) {
                $this->vars['additional_params'][$k] = $v;
            }
        }
    }

    public function render(): string
    {
        $template = $this->outputFormat == 'html'
                    ? "users/findForm.twig"
                    : "users/list.twig";

        return $this->twig->render($this->outputFormat."/$template", $this->vars);
    }
}
