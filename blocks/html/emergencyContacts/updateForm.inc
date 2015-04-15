<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param EmergencyContact $this->contact
 */
use Application\Models\EmergencyContact;
use Blossom\Classes\View;

$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/lib/libphonenumber/build/utils.js');
$this->template->addToAsset('stylesheets', INTL_TEL_INPUT.'/build/css/intlTelInput.css');
$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/build/js/intlTelInput.min.js');
$this->template->addToAsset('scripts',     BASE_URI.'/js/phoneNumbers.js');
?>
<section class="person-emergencyProfile-edit">
    <h1><?php echo $this->_('emergencyContacts_update'); ?></h1>
    <p class="text-help"><?php echo $this->_('emergencyContacts_help', 'messages'); ?></p>
    <form method="post" action="<?php echo BASE_URI; ?>/people/updateEmergencyContacts">
        <?php
            $fields = [
                'email' => 3,
                'sms'   => 2,
                'phone' => 3,
                'tty'   => 1
            ];
            foreach ($fields as $f=>$c) {
                $label = $this->_($f);
                $help  = $this->_($f.'_help', 'messages');
                $type  = $f=='email' ? 'email' : 'tel';
                $minLength = $type=='tel' ? 'minlength="10"' : '';

                echo "<fieldset><legend>$label</legend><p class=\"text-help\">$help</p>";
                for ($i=1; $i<=$c; $i++) {
                    $id    = $f.'_'.$i;
                    $get   = 'get'.ucfirst($id);
                    $value = View::escape($this->contact->$get());

                    echo "
                    <dl><dt><label  for=\"$id\">$label $i</label></dt>
                        <dd><input name=\"$id\" id=\"$id\" type=\"$type\" $minLength value=\"$value\" /></dd>
                    </dl>
                    ";
                }
                echo "</fieldset>";
            }
        ?>
        <fieldset>
            <input name="emergencyContact_id" type="hidden" value="<?php echo $this->contact->getId(); ?>" />
            <input name="username"            type="hidden" value="<?php echo $this->contact->getUsername(); ?>" />
            <?php
                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(
                    BASE_URI.'/people/view?username='.$this->contact->getUsername()
                );
            ?>
        </fieldset>
    </form>
</section>
