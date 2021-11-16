<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\Actions\Info;

use Domain\People\Entities\Person;

class Response
{
    public $person;
    public $errors = [];

    public function __construct(?Person $person=null, ?array $errors=null)
    {
        $this->person = $person;
        $this->errors = $errors;
    }
}
