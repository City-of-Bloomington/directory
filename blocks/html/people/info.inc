<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\User;
use Blossom\Classes\View;
?>
<section class="person-profile">
    <?php
        # Get their photo, if they've got one
        $photo = $this->person->hasPhoto()
            ? "<img src=\"{$this->person->getPhotoUri()}\" />"
            : "";
        #$photo = $this->person->hasLdapPhoto()
        #    ? "<img src=\"".BASE_URI."/people/photo?username={$this->person->getUsername()}\" align=\"left\" />"
        #    : "<img src=\"".BASE_URI."/images/nophoto.jpg\"  align=\"left\" />";
        echo $photo;
    ?>
    <div class="person-details">
        <h1><?php echo View::escape($this->person->getFullname()); ?></h1>
        <h2><?php echo View::escape($this->person->getTitle()); ?></h2>
        <div><?php echo View::escape($this->person->getDepartment()); ?></div>
        <div><?php echo View::escape($this->person->getLocation()); ?></div>
        <div><?php echo View::escape($this->person->getAddress()); ?></div>
        <div>
            <?php
                $email = View::escape($this->person->getEmail());
                echo "<a href=\"mailto:$email\">$email</a>";
            ?>
        </div>
        <table>
            <?php
                foreach ($this->person->getPhones() as $label=>$number) {
                    echo "<th>$label</th><td>$number</td></tr>";
                }
            ?>
        </table>

        <?php
            if (User::isAllowed('people', 'uploadPhoto')) {
                include APPLICATION_HOME.'/blocks/html/people/uploadPhotoForm.inc';
            }
        ?>
    </div>
</section>
