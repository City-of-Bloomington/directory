<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Department;
use Application\Models\DepartmentGateway;

$departments = DepartmentGateway::getTelephoneNumbers();

$out = [];
foreach ($departments as $d) {
    $department = [];
    foreach (Department::getPublishableFields() as $f=>$ldapName) {
        $department[$f] = $d->$f;
    }
    $department['path'] = $d->getPath();
    $out[] = $department;
}
echo json_encode($out, JSON_PRETTY_PRINT);
