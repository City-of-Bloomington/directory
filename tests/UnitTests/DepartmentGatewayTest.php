<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DepartmentGateway;

$_SERVER['SITE_HOME'] = __DIR__;
require_once '../../configuration.inc';

class DepartmentGatewayTest extends PHPUnit_Framework_TestCase
{
    public function testStaffFilteredByRequestingIPAddress()
    {
        global $DIRECTORY_CONFIG;
        $publicGroupName = $DIRECTORY_CONFIG['Employee']['DIRECTORY_PUBLIC_GROUP'];

        $_SERVER['REMOTE_ADDR'] = '10.20.20.25';
        $filter = DepartmentGateway::getPersonFilter();
        $this->assertNotRegExp("|$publicGroupName|", $filter);

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->assertRegExp("|$publicGroupName|", DepartmentGateway::getPersonFilter());
    }
}