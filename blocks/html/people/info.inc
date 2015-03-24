<?php
/**
 * @copyright 2014-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\User;
use Application\Models\Person;
use Blossom\Classes\Block;
use Blossom\Classes\View;
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
    <div class="person-details">
        <h1><?php echo View::escape($this->person->getFullname()); ?></h1>
        <?php
            if (User::isAllowed('people', 'update')) {
                echo '<div class="tools">';
                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink(
                    BASE_URI.'/people/update?username='.$this->person->username,
                    $this->_('person_edit'),
                    'edit'
                );
                echo '</div>';
            }
        ?>
         <h2><?php echo View::escape($this->person->title); ?></h2>
        <div><?php echo View::escape($this->person->department); ?></div>
        <div><?php echo View::escape($this->person->location); ?></div>
        <?php
            $block = new Block('directoryAttributes/contactInfo.inc', ['entry'=>$this->person]);
            echo $block->render('html', $this->template);

            if (User::isAllowed('people', 'uploadPhoto')) {
                include APPLICATION_HOME.'/blocks/html/people/uploadPhotoForm.inc';
            }
        ?>
    </div>
</section>
