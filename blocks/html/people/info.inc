<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\Models\User;
use Blossom\Classes\Block;
?>
<section class="person-profile">
    <div class="person-photoContainer">
        <?php
            # Get their photo, if they've got one
            $photo = $this->person->hasPhoto()
                ? "<img src=\"{$this->person->getPhotoUri()}\" />"
                : "";
            #$photo = $this->person->hasLdapPhoto()
            #    ? "<img src=\"".BASE_URI."/people/photo?username={$this->person->username}\" align=\"left\" />"
            #    : "<img src=\"".BASE_URI."/images/nophoto.jpg\"  align=\"left\" />";
            echo $photo;
        ?>
    </div>
    <section class="person-details">
        <?php
            $this->_include('people/partials/details.inc');

            $block = new Block('directoryAttributes/contactInfo.inc', ['entry'=>$this->person]);
            echo $block->render('html', $this->template);

            if (User::isAllowed('people', 'uploadPhoto')) {
                $this->_include('people/uploadPhotoForm.inc');
            }
        ?>
    </section>
</section>
