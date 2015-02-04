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
?>
<section class="person-profile">
    <div class="person-photoContainer">
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
    </div>
    <div class="person-details">
        <h1><?php echo View::escape($this->person->getFullname()); ?></h1>
        <h2><?php echo View::escape($this->person->title); ?></h2>
        <div><?php echo View::escape($this->person->department); ?></div>
        <div><?php echo View::escape($this->person->location); ?></div>
        <div>
            <address>
                <?php
                echo View::escape($this->person->address)."\n";
                echo View::escape($this->person->city);
                echo View::escape($this->person->state);
                echo View::escape($this->person->zip);
                ?>
            </address>
        </div>
        <div>
            <?php
                $email = View::escape($this->person->email);
                echo "<a href=\"mailto:$email\">$email</a>";
            ?>
        </div>
        <form method="post" action="<?php echo BASE_URI; ?>/people/update">
            <fieldset>
                <input name="username" type="hidden" value="<?php echo $this->person->username; ?>" />
                <?php
                    foreach (Person::$phoneNumberFields as $label) {
                        $number = View::escape($this->person->$label);
                        echo "
                        <dl class=\"input-field mod-text\">
                            <dt><label  for=\"$label\">$label</label></dt>
                            <dd><input name=\"$label\" id=\"$label\" value=\"$number\" /></dd>
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
