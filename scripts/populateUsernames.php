<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DepartmentGateway;
use Application\Models\HRGateway;
use Blossom\Classes\Database;

include '../bootstrap.php';

$people = DepartmentGateway::search(
    $DIRECTORY_CONFIG['Employee']['DIRECTORY_BASE_DN'],
    ['employeeNum'=>'*']
);
foreach ($people as $p) {
    echo "{$p->employeeNum} {$p->username} \n";

    $employee = HRGateway::getEmployee($p->employeeNum);
    if (empty($employee['username'])) {
        $employee['username'] = $p->username;
        HRGateway::saveEmployeeUsername($employee);
    }
}
