<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\Database;

class HRGateway
{
    public static function getEmployees()
    {
        $zend_db = Database::getConnection(false, 'hr');

        $sql = "select e.EmployeeId     as employeeID,
                       e.EmployeeNumber as employeeNumber,
                       e.EmployeeName   as name,
                       e.PositionTitle  as title,
                       e.OrgStructureDescconcatenated as department
                from HR.vwEmployeeInformation e
                where e.vsEmploymentStatusId=258
                order by e.LastName, e.FirstName";
        $result = $zend_db->query($sql)->execute();
        return $result;
    }
}