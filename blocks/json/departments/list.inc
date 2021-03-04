<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Application\Models\DepartmentGateway;

$departments = DepartmentGateway::getDepartments();
$out         = [];
foreach ($departments as $d) {
    $out[] = $d->getData();
}
echo json_encode($out, JSON_PRETTY_PRINT);
