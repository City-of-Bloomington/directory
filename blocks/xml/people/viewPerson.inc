<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param User $this->user
 */
	# Choose the name to display
	if ($this->user->getDisplayName()) { $displayName = $this->user->getDisplayName(); }
	else { $displayName = "{$this->user->getFirstname()} {$this->user->getLastname()}"; }

	# Get their photo, if they've got one
	$photoSRC = $this->user->getPhoto() ? BASE_URL."/photo.php?uid={$this->user->getUID()}" : BASE_URL."/images/nophoto.jpg";


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


	$displayName = View::escape($displayName);
	$title = View::escape($this->user->getTitle());
	$department = View::escape($this->user->getDepartment());
	$category = View::escape($this->user->getBusinessCategory());
echo "
<person>
	<photo src=\"$photoSRC\"/>
	<name>$displayName</name>
	<title>$title</title>
	<department>$department</department>
	<businessCategory>$category</businessCategory>
	<phoneNumbers>
";
	foreach($phones as $label=>$number)
	{
		$label = View::escape($label);
		echo "<phone><label>$label</label><number>$number</number></phone>";
	}

	$office = View::escape($this->user->getOffice());
echo "
	</phoneNumbers>
	<fax>{$this->user->getFax()}</fax>
	<deliveryOffice>$office</deliveryOffice>
	<email>{$this->user->getEmail()}</email>
</person>
";
?>