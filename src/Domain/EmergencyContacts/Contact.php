<?php
/**
 * Data object for a single emergency contact
 *
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\EmergencyContacts;

class Contact
{
    public $id;
    public $username;
    public $firstname;
    public $lastname;

    public $email_1;
    public $email_2;
    public $email_3;
    public $sms_1;
    public $sms_2;
    public $phone_1;
    public $phone_2;
    public $phone_3;
    public $tty_1;

    public function __construct(?array $data=null)
    {
        if ($data) {
            foreach ($this as $k=>$v) {
                if (!empty($data[$k])) {
                    switch ($k) {
                        case 'id':
                            $this->$k = (int)$data[$k];
                        break;
                        default:
                            $this->$k = $data[$k];
                    }
                }
            }
        }
    }
}
