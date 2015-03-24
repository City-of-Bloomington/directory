<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\Person;

$person = [];
foreach (Person::$fields as $f=>$ldapName) {
    $person[$f] = $this->person->$f;
    if ($this->person->hasPhoto()) {
        $person['photo'] = $this->person->getPhotoUrl();
    }
}
echo json_encode($person);
