<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET category
 * Lists all the people in a category
 */
	#----------------------------------------------------------------------------------------------------------
	# Get all the departments in this category
	#----------------------------------------------------------------------------------------------------------
	$departmentResults = ldap_search($LDAP_CONNECTION,LDAP_DN,"businessCategory=$_GET[category]",array("departmentNumber"));
	$departmentEntries = ldap_get_entries($LDAP_CONNECTION,$departmentResults);

	$departments = array();
	foreach($departmentEntries as $department)
	{
		if(isset($department['departmentnumber'][0]) && !in_array($department['departmentnumber'][0],$departments))
		{
			$departments[$department['departmentnumber'][0]] = array();
		}
	}
	ksort($departments);
	foreach($departments as $department=>$array)
	{
		#----------------------------------------------------------------------------------------------------------
		# Get all the deliveryOffices in this department
		#----------------------------------------------------------------------------------------------------------
		$officeResults = ldap_search($LDAP_CONNECTION,LDAP_DN,"(&(businessCategory=$_GET[category])(departmentNumber=$department))",array("physicalDeliveryOfficeName"));
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
			$query = "(&(businessCategory=$_GET[category])(departmentNumber=$department)(physicalDeliveryOfficeName=$office))";
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

		$departments[$department] = $offices;
	}

	$template = isset($_GET['format']) ? new Template($_GET['format'],$_GET['format']) : new Template();
	if ($template->outputFormat == 'html')
	{
		$template->blocks[] = new Block('breadcrumbs.inc',array('category'=>$_GET['category']));
	}
	$template->blocks[] = new Block('people/viewCategory.inc',array('category'=>$_GET['category'],'departments'=>$departments));
	$template->render();
?>