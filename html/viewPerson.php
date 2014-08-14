<?php
/**
 * @copyright 2006-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET uid
 * Displays information for a single person.
 */
$user = $adldap->user()->infoCollection(
    $_GET['uid'], [
        'businesscategory', 'department', 'physicaldeliveryofficename', 'displayname', 'title',
        'telephonenumber', 'givenname', 'sn', 'cn', 'mail', 'facsimiletelephonenumber', 'jpegphoto'
	]
);

$template = isset($_GET['format']) ? new Template($_GET['format'],$_GET['format']) : new Template();

if ($template->outputFormat === 'html') {
    $breadcrumbs = new Block('breadcrumbs.inc');
    $breadcrumbs->category   = $user->businesscategory;
    $breadcrumbs->department = $user->department;
    $breadcrumbs->location   = $user->physicaldeliveryofficename;
    $template->blocks[] = $breadcrumbs;
}

$template->blocks[] = new Block('people/viewPerson.inc',array('user'=>$user));
$template->render();
