<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\User;
use Application\Models\Person;
use Blossom\Classes\View;

$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/lib/libphonenumber/build/utils.js');
$this->template->addToAsset('stylesheets', INTL_TEL_INPUT.'/build/css/intlTelInput.css');
$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/build/js/intlTelInput.min.js');
$this->template->addToAsset('scripts',     BASE_URI.'/js/phoneNumbers.js');
?>
<section class="person-profile">
    <div class="person-photoContainer">
        <?php
            # Get their photo, if they've got one
            $photo = $this->person->hasPhoto()
                ? "<img src=\"{$this->person->getPhotoUri()}\" />"
                : "";
            echo $photo;
        ?>
    </div>
    <div class="person-details">
    <form method="post" action="<?php echo BASE_URI; ?>/people/update">
        <h1><?php echo View::escape($this->person->getFullname()); ?></h1>
        <h2><?php echo View::escape($this->person->title); ?></h2>
        <div><?php echo View::escape($this->person->department); ?></div>
        <div><?php echo View::escape($this->person->location); ?></div>
        <fieldset><legend><?= $this->_('address'); ?></legend>
            <textarea name="address" id="address"><?= View::escape($this->person->address); ?></textarea>
            <?php
                $fields = ['city', 'state', 'zip'];
                foreach ($fields as $f) {
                    $value = View::escape($this->person->$f);
                    echo "
                    <dl><dt><label  for=\"$f\">{$this->_($f)}</label></dt>
                        <dd><input name=\"$f\" id=\"$f\" value=\"$value\" /></dd>
                    </dl>
                    ";
                }
            ?>
            <dl>
        </fieldset>
        <div>
            <?php
                $email = View::escape($this->person->email);
                echo "<a href=\"mailto:$email\">$email</a>";
            ?>
        </div>
        <fieldset>
            <input name="username" type="hidden" value="<?php echo $this->person->username; ?>" />
            <?php
                foreach (Person::$phoneNumberFields as $label) {
                    $number = View::escape($this->person->$label);
                    echo "
                    <dl><dt><label  for=\"$label\">$label</label></dt>
                        <dd><input name=\"$label\" id=\"$label\" value=\"$number\" type=\"tel\" /></dd>
                    </dl>
                    ";
                }

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(BASE_URI.'/people/view?username='.$this->person->username);
            ?>
        </fieldset>
    </form>
    </div>
</section>
