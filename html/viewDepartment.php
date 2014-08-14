<?php
/*
	Lists all the people in a category

	$_GET variables:	category
						department
*/
if (empty($_GET['category'])) {
	$_SESSION['errorMessages'][] = new Exception('missingCategory');
	header('Location: '.BASE_URL);
	exit();
}

	#Get OU of Department
	$search_ou = array("$_GET[department]", "$_GET[category]", 'Departments');
	$results = $adldap->folder()->listing($search_ou, adLDAP::ADLDAP_FOLDER, true, 'user');

	#----------------------------------------------------------------------------------------------------------
	# Get all the deliveryOffices in this department
	#----------------------------------------------------------------------------------------------------------

	array_shift($results);
	$people = array(); $offices = array();
	foreach($results as $obj) {
	    $aduser = $obj['samaccountname'][0];
	    $user = $adldap->user()->infoCollection($aduser, array("physicaldeliveryofficename", "samaccountname", "sn",
		    "givenname", "telephonenumber", "mail", "title", "displayname",
		    "othertelephone"));

	    // Go to next user if the user is a template
	    if (preg_match('/^\*/',$user->givenname)) { continue; }

            if (!($user->physicaldeliveryofficename)) { continue; }
	    $office = $user->physicaldeliveryofficename;

	    if (isset($office)) {
	        if (!array_key_exists($office,$offices)) { $offices[$office] = array(); }

	        $uid = $user->samaccountname;
		if ($uid) {
	            $people[$uid]['givenname'] = $user->givenname;
		    $people[$uid]['sn'] = $user->sn ? $user->sn : "";

	            if ($user->telephonenumber) { $people[$uid]['telephonenumber'] = $user->telephonenumber; }
	            elseif ($user->othertelephone) { $people[$uid]['telephonenumber'] = $user->othertelephone; }
	            else { $people[$uid]['telephonenumber'] = "N/A"; }

		    $people[$uid]['office'] = $office;
                    $people[$uid]['mail'] = $user->mail ? $user->mail : "";
                    $people[$uid]['displayname'] = $user->displayname ? $user->displayname: "{$user->givenname} {$user->sn}";
                    $people[$uid]['title'] = $user->title ? $user->title : "";

		}
	    }
	}

	ksort($people);
	foreach($people as $key => $person) {
	    $office = $person['office'];
	    $offices[$office][$key] = $person;
	}

	ksort($offices);

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
