<?php
/*
	Lists all the people in a department

	$_GET variables:	department
						category
						location
*/
	$people = array();
	$search_ou = array("$_GET[location]", "$_GET[department]", "$_GET[category]", 'Departments');

	$results = $adldap->folder()->listing($search_ou, adLDAP::ADLDAP_FOLDER, false, 'user');

	if ($results['count']) {
	 	array_shift($results);	
		foreach($results as $acct) {
			$uid = $acct['samaccountname'][0];
			$userinfo = $adldap->user()->info($uid, array("physicaldeliveryofficename", "samaccountname", "sn",
				                                                              "givenname", "telephonenumber", "mail", "title"));
			$user = $userinfo[0];	

			if (empty($user['physicaldeliveryofficename'][0])) { continue; }
			if (preg_match('/^\*/',$user['givenname'][0])) { continue; }

			if ($uid and $user['physicaldeliveryofficename'][0] == $_GET['location']) {
				$people[$uid]['givenname'] = $user['givenname'][0];
				if (isset($user['sn'][0])) { $people[$uid]['sn'] = $user['sn'][0]; } 
				else { $people[$uid]['sn'] = ""; }

				if (isset($user['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $user['telephonenumber'][0]; } 
				elseif (isset($user['othertelephone'][0])) { $people[$uid]['telephonenumber'] = $user['othertelephone'][0]; }
				else { $people[$uid]['telephonenumber'] = "N/A"; }

				if (isset($user['mail'][0])) { $people[$uid]['mail'] = $user['mail'][0]; } 
				else { $people[$uid]['mail'] = ""; }

				if (isset($user['displayname'][0]) && $user['displayname'][0]) { $people[$uid]['displayname'] = $user['displayname'][0]; } else { $people[$uid]['displayname'] = "{$user['givenname'][0]} {$user['sn'][0]}"; }
				if (isset($user['title'][0])) { $people[$uid]['title'] = $user['title'][0]; } else { $people[$uid]['title'] = ""; }
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
