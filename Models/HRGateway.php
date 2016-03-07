<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\Database;

class HRGateway
{
    private static $connection;

    public static function getConnection()
    {
        global $DATABASES;

        if (!self::$connection) {
            $db = $DATABASES['hr'];
            self::$connection = new \PDO($db['dsn'], $db['username'], $db['password']);
        }
        return self::$connection;
    }

    /**
     * @param string $sql
     * @return array
     */
    private static function dbQuery($sql)
    {
        $pdo = self::getConnection();

        $result = $pdo->query($sql);
        if (false !== $result) {
            $rows = $result->fetchAll(\PDO::FETCH_ASSOC);
            $result->closeCursor();
            return $rows;
        }
        else {
            print_r($pdo->errorInfo());
            exit();
        }

    }

    /**
     * @return string
     */
    private static function getEmployeeSelect()
    {
        return "select  e.EmployeeId                   as employeeID,
                        e.EmployeeNumber               as employeeNum,
                        e.EmployeeName                 as name,
                        e.LastName                     as lastname,
                        e.FirstName                    as firstname,
                        job.JobTitle                   as title,
                        e.OrgStructureDescconcatenated as department,
                        udf.ValString                  as username
                from HR.vwEmployeeInformation     e
                join HR.vwEmployeeJobWithPosition job on e.EmployeeId=job.EmployeeId
                left join dbo.UDFEntry            udf on e.EmployeeId=udf.AttachedFKey and udf.UDFAttributeID=52 and udf.TableID=66
                where e.vsEmploymentStatusId=258
                and job.IsPrimaryJob = 1
                and job.EffectiveDate     <= GETDATE() and GETDATE() <= job.EffectiveEndDate
                and job.PositionDetailESD <= GETDATE() and GETDATE() <= job.PositionDetailEED";
    }

    public function getEmployees()
    {
        $sql = self::getEmployeeSelect().
                " order by e.LastName, e.FirstName";
        return self::dbQuery($sql);
    }

    /**
     * @return Zend\Db\Result
     */
    public static function getEmployeesWithoutAccounts()
    {
        $sql = self::getEmployeeSelect().
                " and udf.ValString is null
                  order by e.LastName, e.FirstName";
        return self::dbQuery($sql);
    }

    /**
     * Returns the HR database record for a single employee
     *
     * @return array
     */
    public static function getEmployee($employeeNumber)
    {
        $employeeNumber = (int)$employeeNumber;

        $sql  = self::getEmployeeSelect()." and e.EmployeeNumber=$employeeNumber";
        $rows = self::dbQuery($sql);
        if (count($rows)) {
            return $rows[0];
        }
    }

    public static function saveEmployeeUsername($employee)
    {
       if (!empty($employee['employeeID']) && !empty($employee['username'])) {
            // Our db driver does not support bound parameters
            // Take great care with the inputs here
            $tableId     = 66;
            $attributeId = 52;
            $employeeID  = (int)$employee['employeeID'];
            $username    = preg_replace('/[^a-z]/', '', strtolower($employee['username']));

            $sql = "select ValString from dbo.UDFEntry
                    where AttachedFKey=$employeeID
                      and UDFAttributeID=$attributeId
                      and TableID=$tableId";
            $result = self::dbQuery($sql);

            if (count($result)) {
                $row = $result[0];

                if ($row['ValString'] !== $username) {
                    $sql = "update dbo.UDFEntry
                            set ValString='$username', ChangedDate=GETDATE(), ChangedUserID=0
                            where AttachedFKey=$employeeID
                              and UDFAttributeID=$attributeId
                              and TableID=$tableId";
                    self::dbQuery($sql);
                }
            }
            else {
                $sql = "insert into dbo.UDFEntry
                        (TableID, AttachedFKey, UDFAttributeID, ValString, ChangedDate, ChangedUserID)
                        values($tableId, $employeeID, $attributeId, '$username', GETDATE(), 0)";
                self::dbQuery($sql);
            }
       }
    }
}