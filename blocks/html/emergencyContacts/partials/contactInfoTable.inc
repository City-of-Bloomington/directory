<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param EmergencyContact $this->contact
 */
use Application\Models\EmergencyContact;
use Blossom\Classes\View;

$rows = '';
foreach (array_keys(EmergencyContact::$contactFields) as $f) {
    $get = 'get'.ucfirst($f);
    $value = View::escape($this->contact->$get());

    // Format phone numbers
    if ($value && substr($f, 0, 1)!='e') {
        $value = '('.substr($value, 0, 3).') '.substr($value, 3, 3).'-'.substr($value, 6);
    }

    $label = $this->_(substr($f, 0, -2));
    $num   = substr($f, -1);

    if ($value) {
        $rows.= "
        <tr><th>$label $num</th>
            <td>$value</td>
        </tr>
        ";
    }
}
if ($rows) { echo "<table>$rows</table>"; }
