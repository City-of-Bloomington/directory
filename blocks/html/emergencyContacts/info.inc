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
<section class="person-emergencyProfile">
    <header>
        <h2><?php echo $this->_('emergencyContacts'); ?></h2>
        <div class="tools">
            <?php
                if (User::isAllowed('people', 'updateEmergencyContacts')) {
                    $helper = $this->template->getHelper('buttonLink');
                    echo $helper->buttonLink(
                        BASE_URI."/people/updateEmergencyContacts?username={$this->person->getUsername()}",
                        $this->_('emergencyContacts_update'),
                        'edit'
                    );
                }
            ?>
        </div>
    </header>
    <p> <?php echo $this->_('emergencyContacts_help', 'messages'); ?></p>
    <?php
        $rows = '';
        foreach (EmergencyContact::$contactFields as $f) {
            $get = 'get'.ucfirst($f);
            $value = View::escape($contact->$get());

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
    ?>
</section>