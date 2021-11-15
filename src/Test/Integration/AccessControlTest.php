<?php
/**
 * @copyright 2015-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
use PHPUnit\Framework\TestCase;

use Domain\Users\Entities\User;
use Web\View;

class AccessControlTest extends TestCase
{
    public function testStaffCanOnlyEditOwnInfo()
    {
        $_REQUEST['username'] = 'someone';

        $_SESSION['USER'] = new User(['username'=>'test', 'role'=>'Staff']);

        $this->assertFalse(View::isAllowed('people','updateEmergencyContacts'));

        $_REQUEST['username'] = 'test';
        $this->assertTrue(View::isAllowed('people', 'updateEmergencyContacts'),
            'Staff cannot edit their own info'
        );
    }

    public function testAdminCanEditAllInfo()
    {
        $_REQUEST['username'] = 'someone';

        $_SESSION['USER'] = new User(['username'=>'test', 'role'=>'Administrator']);

        $this->assertTrue(View::isAllowed('people'));
    }
}
