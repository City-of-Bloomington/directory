<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\EmergencyContact;
use Application\Models\User;

use Blossom\Classes\View;

$contact = $this->person->getEmergencyContacts();
?>
<section>
    <h1><?php
            echo $this->_('emergencyContacts');

            if (User::isAllowed('people', 'updateEmergencyContacts')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    BASE_URI."/people/updateEmergencyContacts?username={$this->person->getUsername()}",
                    $this->_('emergencyContacts_update'),
                    'edit'
                );
            }
        ?>
    </h1>
    <table>
    <?php
        foreach (EmergencyContact::$contactFields as $f) {
            $get = 'get'.ucfirst($f);
            $value = View::escape($contact->$get());

            $label = $this->_(substr($f, 0, -2));

            echo "
            <tr><th>$label</th>
                <td>$value</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>