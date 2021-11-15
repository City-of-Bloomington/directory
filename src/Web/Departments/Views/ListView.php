<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Departments\Views;

use Web\View;

class ListView extends View
{
    public function __construct(array $depts)
    {
        parent::__construct();

        $this->vars = [
            'departments' => $depts
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/departments/list.twig', $this->vars);
    }
}
