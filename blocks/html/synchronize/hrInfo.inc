<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->hrRecord
 */
use Application\Models\DirectoryAttributes;

$fields = [
    DirectoryAttributes::NAME,
    DirectoryAttributes::TITLE,
    DirectoryAttributes::DEPARTMENT,
    DirectoryAttributes::EMPLOYEENUM
];
foreach ($fields as $f) {
    $$f = self::escape($this->hrRecord[$f]);
}
?>
<header>
    <h1><?= $name; ?></h1>
</header>
 <h2><?= $title;       ?></h2>
<div><?= $department;  ?></div>
<table class="contact">
    <tr><th><?= $this->_(DirectoryAttributes::EMPLOYEENUM); ?></th>
        <td><?= $employeeNum; ?></td>
    </tr>
</table>
