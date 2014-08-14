<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param string $this->category
 * @param array $this->departments
 */
	echo "<ul>";
	foreach($this->departments as $department=>$offices)
	{
		#----------------------------------------------------------------------------------------------------------
		# Get all the deliveryOffices in this department
		#----------------------------------------------------------------------------------------------------------
		echo "<li><a href=\"viewDepartment.php?category={$this->category};department=$department\">$department</a><ul>";
		foreach($offices as $office=>$people)
		{
			#----------------------------------------------------------------------------------------------------------
			# Do the search
			#----------------------------------------------------------------------------------------------------------
			echo "<li><a href=\"viewLocation.php?category={$this->category};department=$department;location=$office\">$office</a><table>";
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
		echo "</ul></li>";
	}
	echo "</ul>";
?>
