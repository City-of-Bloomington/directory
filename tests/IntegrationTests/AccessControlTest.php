<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\User;

require_once '../../configuration.inc';

class AccessControlTest extends PHPUnit_Framework_TestCase
{
    public function testStaffCanOnlyEditOwnInfo()
    {
        $_GET['username'] = 'someone';

        $_SESSION['USER'] = new User();
        $_SESSION['USER']->setUsername('test');
        $_SESSION['USER']->setRole('Staff');

        $this->assertFalse(User::isAllowed('people','updateEmergencyContacts'));

        $_GET['username'] = 'test';
        $this->assertTrue(User::isAllowed('people', 'updateEmergencyContacts'),
            'Staff cannot edit their own info'
        );
    }

    public function testAdminCanEditAllInfo()
    {
        $_GET['username'] = 'someone';

        $_SESSION['USER'] = new User();
        $_SESSION['USER']->setUsername('test');
        $_SESSION['USER']->setRole('Administrator');

        $this->assertTrue(User::isAllowed('people'));
    }
}