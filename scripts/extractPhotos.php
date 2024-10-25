<?php
/**
 * @copyright 2024 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
include '../bootstrap.php';

use Application\Models\DepartmentGateway;

$dn    = 'OU=Utilities,OU=Departments,DC=cob,DC=bloomington,DC=in,DC=gov';
$staff = DepartmentGateway::search($dn, []);
foreach ($staff as $p) {
    $user = $p->username;
    $data = $p->getPhoto();
    echo "$user\n";

    if ($data) {
        file_put_contents("./users/$user.jpg", $data);
    }
}
