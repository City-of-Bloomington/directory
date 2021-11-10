<?php
/**
 * @copyright 2019-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Domain\Users\Entities\User;

use Web\View;

class InfoView extends View
{
    public function __construct(User $user)
    {
        parent::__construct();

        $this->vars = [
            'title' => $user->getFullname(),
            'user'  => $user
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/users/info.twig', $this->vars);
    }
}
