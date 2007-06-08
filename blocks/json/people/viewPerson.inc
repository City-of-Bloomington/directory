<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param LDAPEntry $this->user
 */
	# Choose the name to display
	if ($this->user->getDisplayName()) { $displayName = $this->user->getDisplayName(); }
	else { $displayName = "{$this->user->getFirstname()} {$this->user->getLastname()}"; }


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

	$jsonPhones = array();
	foreach($phones as $label=>$number) { $jsonPhones[] = "{'label':'$label','number':'$number'}"; }
	$jsonPhones = implode(',',$jsonPhones);



echo "{
	'name':'$displayName',
	'title':'{$this->user->getTitle()}',
	'department':'{$this->user->getDepartment()}',
	'businesscategory':'{$this->user->getBusinessCategory()}',
	'phonenumbers':[$jsonPhones],
	'fax':'{$this->user->getFax()}',
	'office':'{$this->user->getOffice()}',
	'email':'{$this->user->getEmail()}'
}
";
?>