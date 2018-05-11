<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DepartmentGateway;

use PHPUnit\Framework\TestCase;

$DIRECTORY_CONFIG = [
    'Employee' => [
        'DIRECTORY_RESTRICTED'   => 'CN=Restricted Group Name',
        'DIRECTORY_PROMOTED'     => 'CN=Public Group Name',
        'DIRECTORY_INTERNAL_IP'  => '^10\.(20|50)\.'
    ]
];

class DepartmentGatewayTest extends TestCase
{
    public function testStaffFilteredByRequestingIPAddress()
    {
        global $DIRECTORY_CONFIG;
        $restrictedGroup = $DIRECTORY_CONFIG['Employee']['DIRECTORY_RESTRICTED'];

        $_SERVER['REMOTE_ADDR'] = '10.20.20.25';
        $filter = DepartmentGateway::getPersonFilter();
        $this->assertNotRegExp("|$restrictedGroup|", $filter);

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->assertRegExp("|$restrictedGroup|", DepartmentGateway::getPersonFilter());
    }
}
