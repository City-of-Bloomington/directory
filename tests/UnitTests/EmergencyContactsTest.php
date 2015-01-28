<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\EmergencyContact;

$_SERVER['SITE_HOME'] = __DIR__;
require_once '../../configuration.inc';

class EmergencyContactsTest extends PHPUnit_Framework_TestCase
{
    public function testEmailValidation()
    {
        $validString   = 'test@somewhere.el.gov';
        $invalidString = '@somewhere.el.gov';

        $contact = new EmergencyContact();
        $this->assertTrue ($contact->isValidEmail($validString  ));
        $this->assertFalse($contact->isValidEmail($invalidString));

        $contact->setUsername('test');
        $contact->setEmail_1($validString);

        try {
            $contact->validate();
            $this->assertTrue(true);
        }
        catch (\Exception $e) {
            $this->assertTrue(false, 'Validate function failed on good data');
        }
    }
}