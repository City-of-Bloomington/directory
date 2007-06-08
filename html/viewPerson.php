<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET uid
 * Displays information for a single person.
 */
	$user = new LDAPEntry($_GET['uid']);

	$template = isset($_GET['format']) ? new Template($_GET['format'],$_GET['format']) : new Template();

	if ($template->outputFormat === 'html')
	{
		$breadcrumbs = new Block('breadcrumbs.inc');
		$breadcrumbs->category = $user->getBusinessCategory();
		$breadcrumbs->department = $user->getDepartment();
		$breadcrumbs->location = $user->getOffice();
		$template->blocks[] = $breadcrumbs;
	}

	$template->blocks[] = new Block('people/viewPerson.inc',array('user'=>$user));
	$template->render();
?>