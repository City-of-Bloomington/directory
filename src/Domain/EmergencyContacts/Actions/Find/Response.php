<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts\Actions\Find;

class Response
{
    public $contacts = [];
    public $errors   = [];

    public function __construct(?array $contacts=null, ?array $errors=null)
    {
        $this->contacts = $contacts;
        $this->errors   = $errors;
    }
}
