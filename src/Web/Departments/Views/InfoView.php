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
            'department'  => $department,
            'breadcrumbs' => self::breadcrumbs($department->path)
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/departments/info.twig', $this->vars);
    }

    private static function breadcrumbs(string $path): array
    {
        $breadcrumbs = [];
        while ($path && $path != '/') {
            $uri  = parent::generateUri('departments.view', ['path'=>$path]);
            $name = ucwords(str_replace('_', ' ', basename($path)));
            $breadcrumbs[$name] = $uri;

            $path = dirname($path);
        }
        return array_reverse($breadcrumbs);
    }
}
