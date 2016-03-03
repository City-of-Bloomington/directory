<?php
/**
 * Renders address, phone number, and email for a directory entry
 *
 * We're using the same markup whether it's a Department or a Person
 *
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param DirectoryAttributes $this->entry
 */
use Application\Models\DirectoryAttributes;
$fields = [
    DirectoryAttributes::ADDRESS,
    DirectoryAttributes::CITY,
    DirectoryAttributes::STATE,
    DirectoryAttributes::ZIP,
    DirectoryAttributes::EMAIL
];
foreach ($fields as $f) {
    $$f = self::escape($this->entry->$f);
}
?>
<div><?= "<address>$address\n$city $state $zip</address>"; ?></div>
<div><?= "<a href=\"mailto:$email\">$email</a>"; ?></div>
<table class="contact">
<?php
    foreach (DirectoryAttributes::$phoneNumberFields as $label) {
        $number = $this->entry->$label;
        if ($number) {
            echo "<tr><th>$label</th><td>$number</td></tr>";
        }
    }
?>
</table>
