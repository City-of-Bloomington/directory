<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Departments\Actions\Info;

use Domain\Departments\Entities\Department;

class Response
{
    public $department;
    public $errors = [];

    public function __construct(?Department $dept=null, ?array $errors=null)
    {
        $this->department = $dept;
        $this->errors     = $errors;
    }
}
