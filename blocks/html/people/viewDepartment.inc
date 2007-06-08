<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->category
	$this->department
	$this->offices
*/
	echo "<ul>";
	foreach($this->offices as $office=>$people)
	{
		echo "<li><a href=\"viewLocation.php?category={$this->category};department={$this->department};location=$office\">$office</a><table>";
		foreach ($people as $uid => $person)
		{
			echo "
			<tr><td><a href=\"viewPerson.php?uid=$uid\">$person[displayname]</a>, $person[title]</td>
				<td>$person[telephonenumber]</td>
				<td><a href=\"mailto:$person[mail]\">$person[mail]</td>
			</tr>
			";
		}
		echo "</table></li>";
	}
	echo "</ul>";
?>