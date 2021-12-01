<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts\Actions\Load;

use Domain\EmergencyContacts\Contact;

class Response
{
    public $contact;
    public $errors = [];

    public function __construct(?Contact $contact=null, ?array $errors=null)
    {
        $this->contact = $contact;
        $this->errors  = $errors;
    }
}
