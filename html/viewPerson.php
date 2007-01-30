<?php
/*
	Displays information for a single person.

	$_GET variables:	uid - User ID of the person to display.
*/
	$user = new LDAPEntry($_GET['uid']);

	$breadcrumbs = new Block('breadcrumbs.inc');
	$breadcrumbs->category = $user->getBusinessCategory();
	$breadcrumbs->department = $user->getDepartment();
	$breadcrumbs->location = $user->getOffice();


	$template = new Template();
	$template->blocks[] = $breadcrumbs;
	$template->blocks[] = new Block('people/viewPerson.inc',array('user'=>$user));
	$template->render();
?>