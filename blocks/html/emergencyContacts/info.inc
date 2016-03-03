<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\User;

use Blossom\Classes\Block;
use Blossom\Classes\View;

$contact = $this->person->getEmergencyContacts();
?>
<section class="person-emergencyProfile">
    <header>
        <h1><?php echo $this->_(['emergencyContact', 'emergencyContacts', 1]); ?></h1>
        <div class="tools">
            <?php
                if (User::isAllowed('people', 'updateEmergencyContacts')) {
                    $helper = $this->template->getHelper('buttonLink');
                    echo $helper->buttonLink(
                        BASE_URI."/people/updateEmergencyContacts?username={$this->person->username}",
                        $this->_('emergencyContacts_update'),
                        'edit'
                    );
                }
            ?>
        </div>
    </header>
    <p class="text-help"><?php echo $this->_('emergencyContacts_help', 'messages'); ?></p>
    <?php
        $block = new Block('emergencyContacts/partials/contactInfoTable.inc', ['contact'=>$contact]);
        echo $block->render('html', $this->template);
    ?>
</section>
