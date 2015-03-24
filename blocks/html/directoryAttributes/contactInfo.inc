<?php
/**
 * Renders address, phone number, and email for a directory entry
 *
 * We're using the same markup whether it's a Department or a Person
 *
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param DirectoryAttributes $this->entry
 */
use Application\Models\DirectoryAttributes;
use Blossom\Classes\View;
$fields = ['address', 'city', 'state', 'zip', 'email'];
foreach ($fields as $f) {
    $$f = View::escape($this->entry->$f);
}
?>
<div>
<?php
    echo "
    <address>$address
    $city $state $zip
    </address>
    ";
?>
</div>
<div>
    <?php
        echo "<a href=\"mailto:$email\">$email</a>";
    ?>
</div>
<table>
    <?php
        foreach (DirectoryAttributes::$phoneNumberFields as $label) {
            $number = $this->entry->$label;
            if ($number) {
                echo "<tr><th>$label</th><td>$number</td></tr>";
            }
        }
    ?>
</table>
