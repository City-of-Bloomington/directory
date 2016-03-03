<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\Database;

class HRGateway
{
    /**
     * Maps the internal application fieldnames to the names
     * used in the HR database.
     * Each key is the internal name
     * Each value is the HR database fieldname
     */
    private static $fields = [
        DirectoryAttributes::EMPLOYEEID  => 'EmployeeId',
        DirectoryAttributes::EMPLOYEENUM => 'EmployeeNumber',
        DirectoryAttributes::NAME        => 'EmployeeName',
        DirectoryAttributes::TITLE       => 'PositionTitle',
        DirectoryAttributes::DEPARTMENT  => 'OrgStructureDescconcatenated'
    ];

    /**
     * Returns the SQL for the fields to be used in a select statement
     *
     * @return string
     */
    private static function getFieldsSql()
    {
        $f = [];
        foreach (self::$fields as $k=>$v) { $f[] = "$v as $k"; }
        return implode(',', $f);
    }

    /**
     * @return Zend\Db\Result
     */
    public static function getEmployees()
    {
        $zend_db = Database::getConnection(false, 'hr');

        $fields = self::getFieldsSql();
        $sql = "select $fields
                from HR.vwEmployeeInformation
                where vsEmploymentStatusId=258
                order by LastName, FirstName";
        $result = $zend_db->query($sql)->execute();
        return $result;
    }

    /**
     * Returns the HR database record for a single employee
     *
     * @return array
     */
    public static function getEmployee($employeeNumber)
    {
        $employeeNumber = (int)$employeeNumber;

        $zend_db = Database::getConnection(false, 'hr');

        $fields = self::getFieldsSql();
        $sql = "select $fields
                from HR.vwEmployeeInformation
                where EmployeeNumber=$employeeNumber";
        $result = $zend_db->query($sql)->execute();
        if (count($result)) {
            $row = $result->current();
            return $row;
        }
    }
}