<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\DepartmentGateway;
use Application\Models\HRGateway;

include __DIR__.'/../bootstrap.php';

$people    = DepartmentGateway::getPeople($DIRECTORY_CONFIG['Employee']['DIRECTORY_BASE_DN']);
$employees = [];
$em = HRGateway::getEmployees();
foreach ($em as $e) {
    $employees[$e['employeeNum']] = $e;
}

foreach ($people as $person) {
    $employeeNum = $person->employeeNum;

    if ($employeeNum && array_key_exists($employeeNum, $employees)) {
        if ($person->title !== $employees[$employeeNum]['title']) {
            #echo "{$person->username}: {$person->title} >> {$employees[$employeeNum]['title']}\n";
            $person->title = $employees[$employeeNum]['title'];
            $person->save();
        }
    }
}
