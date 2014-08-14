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
  		
	$departments = array();
	$category = "$_GET[category]";
	$results = $adldap->folder()->listing(array($category, 'Departments'), adLDAP::ADLDAP_FOLDER, false, 'folder');

	array_shift($results);

	// Get department name from the DN 
	foreach($results as $ou) 
	{
		$exp = explode(',',$ou['distinguishedname'][0]);
		$department_name = substr($exp[0],3);

		if (preg_match('/^\*/',$department_name)) { continue; }
		$departments[$department_name] = array();
	}

	ksort($departments);
	#----------------------------------------------------------------------------------------------------------
	# Get all the deliveryOffices in this department
	#----------------------------------------------------------------------------------------------------------
	foreach($departments as $department=>$array)
	{
		$offices = array();
		$results = $adldap->folder()->listing(array($department, $category, 'Departments'), adLDAP::ADLDAP_FOLDER, false, 'folder');
		array_shift($results);

		foreach($results as $ou) {	
			$exp = explode(',',$ou['distinguishedname'][0]);
		   	$office_name = substr($exp[0],3);
			$offices[$office_name] = array();
		}

		ksort($offices);

	#----------------------------------------------------------------------------------------------------------
	# Get all the people in each office
	#----------------------------------------------------------------------------------------------------------
		foreach($offices as $office=>$array)
		{
			$people = array();
			$search_ou = array($office, $department, $category, 'Departments');

			$results = $adldap->folder()->listing($search_ou, adLDAP::ADLDAP_FOLDER, false, 'user');
			array_shift($results);

			foreach($results as $entry)
			{
				$uid = trim($entry['samaccountname'][0]);
				$userinfo = $adldap->user()->info($uid, array("physicaldeliveryofficename", "samaccountname", "sn",
                                                              "givenname", "telephonenumber", "mail", "title", "othertelephone", "displayname"));

				$user = $userinfo[0];
				if (preg_match('/^\*/',$user['givenname'][0])) { continue; }	

				if ($uid and $user['physicaldeliveryofficename'][0] == $office)
				{
					$people[$uid]['givenname'] = $user['givenname'][0];
					$people[$uid]['sn'] = isset($user['sn'][0]) ? $user['sn'][0] : "";
				}
				if (isset($user['telephonenumber'][0])) 
				{
				    $people[$uid]['telephonenumber'] = $user['telephonenumber'][0];
				} 
				elseif (isset($userinfo[0]['othertelephone'][0])) 
				{
				    $people[$uid]['telephonenumber'] = $user['othertelephone'][0];
				}
				else { $people[$uid]['telephonenumber'] = "N/A"; }

				$people[$uid]['mail'] = isset($user['mail'][0]) ? $user['mail'][0] : "";

				$people[$uid]['displayname'] = isset($user['displayname'][0]) ? 
						$user['displayname'][0] : "{$user['givenname'][0]} {$user['sn'][0]}";

				$people[$uid]['title'] = isset($user['title'][0]) ? $user['title'][0] : "";
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
