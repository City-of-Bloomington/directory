<?php
/**
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\Models\User;
use Application\Models\Person;

$this->template->addToAsset('scripts',     JQUERY);
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
    <form method="post" action="<?= BASE_URI; ?>/people/update">
        <h1> <?= self::escape($this->person->getFullname()); ?></h1>
        <h2> <?= self::escape($this->person->title); ?></h2>
        <div><?= self::escape($this->person->department); ?></div>
        <div><?= self::escape($this->person->location); ?></div>
        <fieldset><legend><?= $this->_('address'); ?></legend>
            <textarea name="address" id="address"><?= self::escape($this->person->address); ?></textarea>
            <?php
                $fields = ['city', 'state', 'zip'];
                foreach ($fields as $f) {
                    $value = self::escape($this->person->$f);
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
                $email = self::escape($this->person->email);
                echo "<a href=\"mailto:$email\">$email</a>";
            ?>
        </div>
        <fieldset>
            <input name="username" type="hidden" value="<?= $this->person->username; ?>" />
            <?php
                foreach (Person::$phoneNumberFields as $label) {
                    $number  = self::escape($this->person->$label);
                    echo "
                    <dl><dt><label  for=\"$label\">{$this->_($label)}</label></dt>
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
