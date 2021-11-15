<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Departments\Views;

use Web\View;
use Domain\Departments\Entities\Department;

class InfoView extends View
{
    public function __construct(Department $department)
    {
        parent::__construct();
        $this->vars = [
            'department' => $department
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/departments/info.twig', $this->vars);
    }
}
