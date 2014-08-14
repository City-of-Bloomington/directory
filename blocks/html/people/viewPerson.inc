<?php
/**
 * @copyright 2006-2014 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param User $this->user
 */
# Choose the name to display
$displayName = $this->user->displayname
    ? $this->user->displayname
    : "{$this->user->givenname} {$this->user->sn}";

# Get their photo, if they've got one
$photo = $this->user->jpegphoto
    ? "<img src=\"photo.php?uid={$this->user->cn}\" alt=\"{$this->user->cn}\" />"
    : "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />";


# Figure out what phone numbers to show
$phones = [];
if (preg_match("/^10\./",$_SERVER['REMOTE_ADDR'])) {
    # Internal website rules
    # Show all three numbers, if we've got em
    $phones['phone'] = $this->user->telephonenumber;
    if ($this->user->othertelephone && !isset($phones['phone'])) {
        $phones['phone'] = $this->user->othertelephone;
    }

    if ($this->user->webphone) {
        $phones['website phone'] = $this->user->webphone;
    }
}
else {
    # External website rules
    $phones['phone'] = $this->user->webphone;
}

echo "
<table id=\"details\">
<tr><th>$photo</th>
    <td><h2>$displayName</h2>
        <h3>{$this->user->title}</h3>
        <h3>{$this->user->department}</h3>
        <h3>{$this->user->BusinessCategory}</h3>
    </td></tr>
";
foreach($phones as $label=>$number)
{
    echo "
    <tr><th>$label</th>
        <td>$number</td></tr>
    ";
}
echo "
<tr><th>Fax</th>
    <td>{$this->user->facsimiletelephonenumber}</td></tr>
<tr><th>Delivery Office</th>
    <td>{$this->user->physicaldeliveryofficename}</td></tr>
<tr><th>Email</th>
    <td><a href=\"mailto:{$this->user->mail}\">{$this->user->mail}</a></td></tr>
</table>
";

if (userHasRole("Administrator")) {
    echo "<div><button type=\"button\" class=\"editLarge\" onclick=\"document.location.href='updatePerson.php?uid={$this->user->cn}';\">Edit</button></div>";
}
