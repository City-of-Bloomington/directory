<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Department $this->department
 */
use Application\Models\Department;

$department = [];
foreach (Department::$fields as $f=>$ldapName) {
    $department[$f] = $this->department->$f;
}
echo json_encode($department);
