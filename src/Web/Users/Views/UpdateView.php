<?php
/**
 * @copyright 2019-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Domain\Users\Actions\Update\Request;
use Domain\Users\Actions\Update\Response;
use Web\View;

class UpdateView extends View
{
    public function __construct(Request   $request,
                                ?Response $response,
                                array     $roles,
                                array     $authentication_methods)
    {
        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        
        parent::__construct();

        $this->vars = array_merge((array)$request, [
            'title'                  => $request->id ? $this->_('user_edit') : $this->_('user_add'),
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods
        ]);
    }

    public function render(): string
    {
        return $this->twig->render('html/users/updateForm.twig', $this->vars);
    }
}
