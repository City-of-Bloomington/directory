<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->people
*/
	if (count($this->people))
	{
		echo "<table>";
		foreach ($this->people as $uid => $person)
		{
			# Choose the name to display
			if (isset($person['displayname']) and ($person['displayname'][0])) { $displayName = $person['displayname'][0]; }
			else { $displayName = "{$person['givenname'][0]} {$person['sn'][0]}"; }

			echo "
			<tr><td><a href=\"viewPerson.php?uid=$uid\">$person[displayname]</a>, $person[title]</td>
				<td>$person[telephonenumber]</td>
				<td><a href=\"mailto:$person[mail]\">$person[mail]</td>
			</tr>
			";
		}
		echo "</table>";
	}
	else { echo "<h1>No Matches Found</h1>"; }
?>