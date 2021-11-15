<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;
use Web\Departments\LdapDepartmentGateway as Gateway;

class DepartmentsGatewayTest extends TestCase
{
    public function entries()
    {
        return [
            ['/bloomington_transit', 'OU=Bloomington Transit,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['/utilities/blucher_poole', 'OU=Blucher Poole,OU=Utilities,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['/city_hall/parks_and_recreation/cascades_golf_course', 'OU=Cascades Golf Course,OU=Parks and Recreation,OU=City Hall,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['/city_hall', 'OU=City Hall,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['/utilities/communication', 'OU=Communication,OU=Utilities,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['/police/departmental_application_accounts', 'OU=Departmental Application Accounts,OU=Police,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov'],
            ['', 'OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov']
        ];
    }
    /**
     * @dataProvider entries
     */
    public function testPathForDn($path, $dn)
    {
        global $DI;
        $gw = $DI->get('Domain\Departments\DataStorage\DepartmentsGateway');
        $p  = $gw->pathForDn($dn);
        $this->assertEquals($path, $p);
    }
}
