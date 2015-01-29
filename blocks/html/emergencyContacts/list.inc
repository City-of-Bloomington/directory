<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\Results $this->contacts
 */
use Application\Models\EmergencyContact;
use Blossom\Classes\Block;
use Blossom\Classes\Url;
?>
<section>
    <h1><?php echo $this->_(['emergencyContact', 'emergencyContacts', 2]); ?></h1>
    <div>
    <?php
        $helper = $this->template->getHelper('buttonLink');
        $url = new Url(Url::current_url());
        $url->format = 'csv';
        echo $helper->buttonLink($url, 'CSV', 'download');
    ?>
    </div>
    <?php
        $block = new Block('emergencyContacts/partials/contactInfoTable.inc');

        foreach ($this->contacts as $contact) {
            echo "<div><h2>{$contact->getUsername()}</h2>";

            $block->contact = $contact;
            echo $block->render('html', $this->template);

            echo "</div>";
        }
    ?>
</section>