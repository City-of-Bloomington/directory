<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Person $this->person
 */
declare (strict_types=1);
use Application\Models\Person;

echo "<table>";
foreach ([Person::EMPLOYEENUM, Person::EMPLOYEEID] as $f) {
    $v = parent::escape($this->person->$f);
    echo "<tr><th>$f</th><td>$v</td></tr>";
}
echo "</table>";
