<?php
/**
 * @copyright 2015-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
use PHPUnit\Framework\TestCase;

use Application\Authentication\Auth;
use Application\Models\User;

class AccessControlTest extends TestCase
{
    public function testReadUserFromSession()
    {
        $_SESSION['USER'] = new User();
        $_SESSION['USER']->setUsername('test');
        $_SESSION['USER']->setRole('Staff');

        $user = Auth::getAuthenticatedUser();

        $this->assertEquals($_SESSION['USER']->getUsername(), $user->getUsername());
    }

    public function testStaffCanOnlyEditOwnInfo()
    {
        $_REQUEST['username'] = 'someone';

        $_SESSION['USER'] = new User();
        $_SESSION['USER']->setUsername('test');
        $_SESSION['USER']->setRole('Staff');

        $this->assertFalse(User::isAllowed('people','updateEmergencyContacts'));

        $_REQUEST['username'] = 'test';
        $this->assertTrue(User::isAllowed('people', 'updateEmergencyContacts'),
            'Staff cannot edit their own info'
        );
    }

    public function testAdminCanEditAllInfo()
    {
        $_REQUEST['username'] = 'someone';

        $_SESSION['USER'] = new User();
        $_SESSION['USER']->setUsername('test');
        $_SESSION['USER']->setRole('Administrator');

        $this->assertTrue(User::isAllowed('people'));
    }
}
