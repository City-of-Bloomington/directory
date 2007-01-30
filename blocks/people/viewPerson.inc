<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->user
*/
	# Choose the name to display
	if ($this->user->getDisplayName()) { $displayName = $this->user->getDisplayName(); }
	else { $displayName = "{$this->user->getFirstname()} {$this->user->getLastname()}"; }

	# Get their photo, if they've got one
	if ($this->user->getPhoto()) { $photo = "<img src=\"photo.php?uid={$this->user->getUID()}\" alt=\"{$this->user->getUID()}\" />"; }
	else { $photo = "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />"; }


	# Figure out what phone numbers to show
	$phones = array();
	if (ereg("^10\.",$_SERVER['REMOTE_ADDR']))
	{
		# Internal website rules
		# Show all three numbers, if we've got em
		$phones['phone'] = $this->user->getPhone();
		if ($this->user->getPreferredPhone()) { $phones['preferred phone'] = $this->user->getPreferredPhone(); }
		if ($this->user->getWebPhone()) { $phones['website phone'] = $this->user->getWebPhone(); }
	}
	else
	{
		# External website rules
		$phones['phone'] = $this->user->getWebPhone();
	}

	echo "
	<table id=\"details\">
	<tr><th>$photo</th>
		<td><h1>$displayName</h1>
			<h3>{$this->user->getTitle()}</h3>
			<h3>{$this->user->getDepartment()}</h3>
			<h3>{$this->user->getBusinessCategory()}</h3>
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
		<td>{$this->user->getFax()}</td></tr>
	<tr><th>Delivery Office</th>
		<td>{$this->user->getOFfice()}</td></tr>
	<tr><th>Email</th>
		<td><a href=\"mailto:{$this->user->getEmail()}\">{$this->user->getEmail()}</a></td></tr>
	</table>
	";

	if (userHasRole("Administrator"))
	{
		echo "<div><button type=\"button\" class=\"editLarge\" onclick=\"document.location.href='updatePerson.php?uid={$this->user->getUID()}';\">Edit</button></div>";
	}
?>