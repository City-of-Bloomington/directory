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

$class = get_class($this->entry);
$publishable = array_keys($class::getPublishableFields());

$fields = [
    DirectoryAttributes::ADDRESS,
    DirectoryAttributes::CITY,
    DirectoryAttributes::STATE,
    DirectoryAttributes::ZIP,
    DirectoryAttributes::EMAIL
];
foreach ($fields as $f) {
    $$f = in_array($f, $publishable) ? self::escape($this->entry->$f) : '';
}
echo "
<div><address>$address\n$city $state $zip</address></div>
<div><a href=\"mailto:$email\">$email</a></div>
";

$rows = '';
foreach (DirectoryAttributes::$phoneNumberFields as $label) {
    if (in_array($label, $publishable)) {
        $number = $this->entry->$label;
        if ($number) {
            $label = $this->_($label);
            $rows.= "<tr><th>$label</th><td>$number</td></tr>";
        }
    }
}
if ($rows) {
    echo "<table class=\"contact\">$rows</table>";
}
