<?php
/*
	Lists all the people in a category

	$_GET variables:	category
						department
*/
	#----------------------------------------------------------------------------------------------------------
	# Get all the deliveryOffices in this department
	#----------------------------------------------------------------------------------------------------------
	$officeResults = ldap_search($LDAP_CONNECTION,LDAP_DN,"(&(businessCategory=$_GET[category])(departmentNumber=$_GET[department]))",array("physicalDeliveryOfficeName"));
	$officeEntries = ldap_get_entries($LDAP_CONNECTION,$officeResults);

	$offices = array();
	foreach($officeEntries as $office)
	{
		$name = trim($office['physicaldeliveryofficename'][0]);
		if ($name && !array_key_exists($name,$offices)) { $offices[$name] = array(); }
	}
	ksort($offices);

	#----------------------------------------------------------------------------------------------------------
	# Get all the people in each office
	#----------------------------------------------------------------------------------------------------------
	foreach($offices as $office=>$array)
	{
		$query = "(&(businessCategory=$_GET[category])(departmentNumber=$_GET[department])(physicalDeliveryOfficeName=$office))";
		$searchResults = ldap_search($LDAP_CONNECTION,LDAP_DN,$query);
		$entries = ldap_get_entries($LDAP_CONNECTION, $searchResults);
		$people = array();

		foreach($entries as $entry)
		{
			$uid = trim($entry['uid'][0]);
			if ($uid)
			{
				$people[$uid] = array("givenname"=>$entry['givenname'][0], "sn"=>$entry['sn'][0]);
				$people[$uid]['telephonenumber'] = isset($entry['telephonenumber'][0]) ? $entry['telephonenumber'][0] : "";
				$people[$uid]['mail'] = isset($entry['mail'][0]) ? $entry['mail'][0] : "";
				$people[$uid]['displayname'] = isset($entry['displayname'][0]) ? $entry['displayname'][0] : "{$entry['givenname'][0]} {$entry['sn'][0]}";
				$people[$uid]['title'] = isset($entry['title'][0]) ? $entry['title'][0] : "{$entry['givenname'][0]} {$entry['sn'][0]}";
			}
		}
		ksort($people);
		$offices[$office] = $people;
	}


	$department = new Block('people/viewDepartment.inc');
	$department->category = $_GET['category'];
	$department->department = $_GET['department'];
	$department->offices = $offices;

	$breadcrumbs = new Block('breadcrumbs.inc');
	$breadcrumbs->category = $_GET['category'];
	$breadcrumbs->department = $_GET['department'];

	$template = new Template();
	$template->blocks[] = $breadcrumbs;
	$template->blocks[] = $department;
	$template->render();
?>