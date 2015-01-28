<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\EmergencyContact;
use Blossom\Classes\View;

$contact = $this->person->getEmergencyContacts();
?>
<section class="person-emergencyProfile">
    <h2><?php echo $this->_('emergencyContacts_update'); ?></h2>
    <p class="text-help"><?php echo $this->_('emergencyContacts_help', 'messages'); ?></p>
    <form method="post" action="<?php echo BASE_URI; ?>/people/updateEmergencyContacts">
        <fieldset><legend><?php echo $this->_('emergencyContacts'); ?></legend>
            <input name="emergencyContact_id" type="hidden" value="<?php echo $contact->getId(); ?>" />
            <input name="username"            type="hidden" value="<?php echo $this->person->getUsername(); ?>" />
            <?php

                foreach (EmergencyContact::$contactFields as $f) {
                    $get = 'get'.ucfirst($f);
                    $value = View::escape($contact->$get());

                    $label = $this->_(substr($f, 0, -2));
                    $num = substr($f, -1);
                    $inputHelper = $this->template->getHelper('Input');
                    echo $inputHelper->text($f, $label.' '.$num, $value);
                }

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(
                    BASE_URI.'/people/view?username='.$this->person->getUsername()
                );
            ?>
        </fieldset>
    </form>
</section>