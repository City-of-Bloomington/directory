<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param string $this->category
 * @param array $this->departments
 */
 	$category = View::escape($this->category);
	echo "<category name=\"$category\">";
	foreach($this->departments as $department=>$offices)
	{
		$department = View::escape($department);
		echo "<department name=\"$department\">";
		foreach($offices as $office=>$people)
		{
			$office = View::escape($office);
			echo "<office name=\"$office\">";
			foreach ($people as $uid => $person)
			{
				$name = View::escape($person['displayname']);
				$title = View::escape($person['title']);
				$phone = View::escape($person['telephonenumber']);
				$email = View::escape($person['mail']);
				echo "
				<person>
					<username>$uid</username>
					<name>$name</name>
					<title>$title</title>
					<phone>$phone</phone>
					<email>$email</email>
				</person>
				";
			}
			echo "</office>";
		}
		echo "</department>";
	}
	echo "</category>";
?>