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
foreach ($people as $p) {
    echo "{$p->username}:";
    $contact = $repo->load($p->username);
    if ($contact) {
        echo ":{$contact->id}\n";
        $contact->firstname =  $p->firstname;
        $contact->lastname  =  $p->lastname;
        $repo->save($contact);
    }
    else {
        echo ":No Contact Info\n";
        continue;
    }
}
