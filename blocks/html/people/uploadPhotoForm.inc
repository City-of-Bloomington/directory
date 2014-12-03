<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\Person;
?>
<form method="post" action="<?php echo BASE_URI; ?>/people/uploadPhoto" enctype="multipart/form-data">
    <fieldset><legend><?php echo $this->_('uploadPhoto'); ?></legend>
        <p>
        <?php
            echo sprintf(
                $this->_('photo_type_requirements', 'messages'),
                implode(', ', Person::$validPhotoFormats)
            );
        ?>
        </p>
        <input name="username" type="hidden" value="<?php echo $this->person->getUsername(); ?>" />
        <input name="photo" type="file" />
        <button type="submit"><?php echo $this->_('upload'); ?></button>
    </fieldset>
</form>