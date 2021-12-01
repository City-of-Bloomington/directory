<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
include '../../src/Web/bootstrap.php';

$gw     = $DI->get('Domain\Departments\DataStorage\DepartmentsGateway');
$repo   = $DI->get('Domain\EmergencyContacts\Repository');

$people = $gw->search($gw->base_dn(), []);
$staff  = [];
foreach ($people as $p) { $staff[] = $p->username; }

$contacts = $repo->find();
foreach ($contacts as $c) {
    if (!in_array($c->username, $staff)) {
        echo "Deleting {$c->username}\n";
        $repo->delete($c->id);
    }
}
