<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\Actions\Delete;

class Response
{
    public $errors = [];

    public function __construct(?array $errors=null)
    {
        if ($errors) { $this->errors = $errors; }
    }
}
