<?php
/*
	Lists all the people in a department

	$_GET variables:	department
						category
						location
*/
	$people = array();

	# Do the search
	$results = ldap_search($LDAP_CONNECTION,LDAP_DN,"(&(businessCategory=$_GET[category])(departmentNumber=$_GET[department])(physicalDeliveryOfficeName=$_GET[location]))");
	if (ldap_count_entries($LDAP_CONNECTION,$results))
	{
		$entries = ldap_get_entries($LDAP_CONNECTION, $results);
		foreach($entries as $user)
		{
			$uid = $user['uid'][0];
			if ($uid)
			{
				$people[$uid] = array("givenname"=>$user['givenname'][0], "sn"=>$user['sn'][0]);
				if (isset($user['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $user['telephonenumber'][0]; } else { $people[$uid]['telephonenumber'] = ""; }
				if (isset($user['mail'][0])) { $people[$uid]['mail'] = $user['mail'][0]; } else { $people[$uid]['mail'] = ""; }
				if (isset($user['displayname'][0]) && $user['displayname'][0]) { $people[$uid]['displayname'] = $user['displayname'][0]; } else { $people[$uid]['displayname'] = "{$user['givenname'][0]} {$user['sn'][0]}"; }
				if (isset($user['title'][0]) && $user['title'][0]) { $people[$uid]['title'] = $user['title'][0]; } else { $people[$uid]['title'] = "{$user['givenname'][0]} {$user['sn'][0]}"; }
			}
		}
		ksort($people);
	}


	$breadcrumbs = new Block('breadcrumbs.inc');
	$breadcrumbs->category = $_GET['category'];
	$breadcrumbs->department = $_GET['department'];
	$breadcrumbs->location = $_GET['location'];

	$template = new Template();
	$template->blocks[] = $breadcrumbs;
	$template->blocks[] = new Block('people/viewLocation.inc',array('people'=>$people));
	$template->render();
?>