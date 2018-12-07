<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class HRGateway
{
    const UDFAttributeID = 52;
    const TableID        = 66;
    const vsEmploymentStatusId = 258;

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
    public static function dbQuery($sql)
    {
        $pdo = self::getConnection();

        $result = $pdo->query($sql);
        if (false !== $result) {
            $rows = $result->fetchAll(\PDO::FETCH_ASSOC);
            $result->closeCursor();
            return $rows;
        }
        else {
            $errorInfo = $pdo->errorInfo();
            throw new \Exception($errorInfo[2]);
        }

    }

    /**
     * @return string
     */
    private static function getEmployeeSelect()
    {
        $attributeId = self::UDFAttributeID;
        $tableId     = self::TableID;
        $statusId    = self::vsEmploymentStatusId;

        return "select  e.EmployeeId                   as employeeID,
                        e.EmployeeNumber               as employeeNum,
                        n.EmployeeNameId               as employeeNameID,
                        e.EmployeeName                 as name,
                        e.LastName                     as lastname,
                        e.FirstName                    as firstname,
                        isnull(x.Title, job.JobTitle)  as title,
                        e.OrgStructureDescconcatenated as department,
                        udf.ValString                  as username
                from HR.vwEmployeeInformation     e
                join HR.vwEmployeeJobWithPosition job  on e.EmployeeId=job.EmployeeId
                                                      and job.IsPrimaryJob = 1
                                                      and GETDATE() between job.EffectiveDate     and job.EffectiveEndDate
                                                      and GETDATE() between job.PositionDetailESD and job.PositionDetailEED
                left join COB.jobTitleCrosswalk   x    on job.JobTitle=x.Code
                join HR.EmployeeName              n    on e.EmployeeId=n.EmployeeId
                                                      and GETDATE() between   n.EffectiveDate     and   n.EffectiveEndDate
                left join dbo.UDFEntry            udf  on n.EmployeeNameId=udf.AttachedFKey and udf.UDFAttributeID=$attributeId and udf.TableID=$tableId
                where e.vsEmploymentStatusId=$statusId";
    }

    public static function getEmployees()
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
                " and udf.ValString is null or udf.ValString=''
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
       if (!empty($employee['employeeNameID']) && !empty($employee['username'])) {
            // Our db driver does not support bound parameters
            // Take great care with the inputs here
            $tableId         = self::TableID;
            $attributeId     = self::UDFAttributeID;
            $employeeNameID  = (int)$employee['employeeNameID'];
            $username        = preg_replace('/[^a-z\.]/', '', strtolower($employee['username']));

            $sql = "select ValString from dbo.UDFEntry
                    where AttachedFKey=$employeeNameID
                      and UDFAttributeID=$attributeId
                      and TableID=$tableId";
            $result = self::dbQuery($sql);

            if (count($result)) {
                $row = $result[0];

                if ($row['ValString'] !== $username) {
                    $sql = "update dbo.UDFEntry
                            set ValString='$username', ChangedDate=GETDATE(), ChangedUserID=0
                            where AttachedFKey=$employeeNameID
                              and UDFAttributeID=$attributeId
                              and TableID=$tableId";
                    self::dbQuery($sql);
                }
            }
            else {
                $sql = "insert into dbo.UDFEntry
                              (TableID,  AttachedFKey,  UDFAttributeID,  ValString, ChangedDate, ChangedUserID)
                        values($tableId, $employeeNameID, $attributeId, '$username', GETDATE(),  0)";
                self::dbQuery($sql);
            }
       }
    }
}
