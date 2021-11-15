<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Departments\Actions\Search;

class Response
{
    public $departments = [];
    public $errors      = [];

    public function __construct($departments, ?array $errors=null)
    {
        $this->departments = $departments;
        $this->errors      = $errors;
    }
}
