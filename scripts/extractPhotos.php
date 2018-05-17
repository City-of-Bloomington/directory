<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../bootstrap.php';

use Application\Models\DepartmentGateway;

$gateway = new DepartmentGateway();

$people = $gateway->getPeople();
foreach ($people as $p) {
    $username = $p->username;
    echo "$username\n";
    $data = $gateway->getPhoto($username);
    if ($data) {
        file_put_contents("./users/$username.jpg", $data);
    }
}
