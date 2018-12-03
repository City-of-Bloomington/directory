<?php
/**
 * @copyright 2015-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Person $this->person
 */
use Application\Models\User;
use Application\Models\Person;

$this->template->addToAsset('scripts',     JQUERY);
$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/lib/libphonenumber/build/utils.js');
$this->template->addToAsset('stylesheets', INTL_TEL_INPUT.'/build/css/intlTelInput.css');
$this->template->addToAsset('scripts',     INTL_TEL_INPUT.'/build/js/intlTelInput.min.js');
$this->template->addToAsset('scripts',     BASE_URI.'/js/phoneNumbers.js');

$fullname = parent::escape($this->person->getFullname());
$editable = Person::getEditableFields();
$fields   = array_merge(
    [Person::TITLE, Person::DEPARTMENT, Person::LOCATION, Person::EMAIL, Person::USERNAME],
    $editable
);
foreach ($fields as $f) {
    $$f = parent::escape($this->person->$f);
}
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
        <h1> <?= $fullname;   ?></h1>
        <h2> <?= $title;      ?></h2>
        <div><?= $department; ?></div>
        <div><?= $location;   ?></div>

        <fieldset><legend><?= $this->_('address'); ?></legend>
            <textarea name="address" id="address"><?= $address; ?></textarea>
            <?php
                $fields = [Person::CITY, Person::STATE, Person::ZIP];
                foreach ($fields as $f) {
                    echo "
                    <dl><dt><label  for=\"$f\">{$this->_($f)}</label></dt>
                        <dd><input name=\"$f\" id=\"$f\" value=\"{$$f}\" /></dd>
                    </dl>
                    ";
                }
            ?>
            <dl>
        </fieldset>
        <fieldset><legend><?= $this->_('hr'); ?></legend>
            <div><?= "<a href=\"mailto:$email\">$email</a>";?></div>
            <?php
                $f = Person::EMPLOYEEID;
                if (in_array($f, $editable)) {
                    echo "
                    <dl><dt><label  for=\"$f\">{$this->_($f)}</label></dt>
                        <dd><input name=\"$f\" id=\"$f\" value=\"{$$f}\" /></dd>
                    </dl>
                    ";
                }
            ?>
        </fieldset>
        <fieldset>
            <legend><?= $this->_(['phone', 'phones', count(Person::$phoneNumberFields)]); ?></legend>
            <input name="username" type="hidden" value="<?= $username; ?>" />
            <?php
                foreach (Person::$phoneNumberFields as $label) {
                    echo "
                    <dl><dt><label  for=\"$label\">{$this->_($label)}</label></dt>
                        <dd><input name=\"$label\" id=\"$label\" value=\"{$$label}\" type=\"tel\" /></dd>
                    </dl>
                    ";
                }

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(BASE_URI.'/people/view?username='.$username);
            ?>
        </fieldset>
    </form>
    </div>
</section>
