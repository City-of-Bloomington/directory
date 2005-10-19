<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  search.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  Performs a search. If multiple hits are returned,
#  --                  displays a list of names. If a single hit is returned,
#  --                  redirects to that person's info page. If no hits are
#  --                  returned, displays an error message.
#  --
#  --     Parameters:
#  --      firstName - Full or partial first name of person to locate.
#  --       lastName - Full or partial last name of person to locate.
#  --      extension - Four-digit extension of person to locate. Ignored if
#  --                  firstName or lastName are specified.
#  --

include('errors.inc.php');
include('connect.inc.php');


function sanitize($str) {
	$tmp = str_replace('\\', '\\\\', $str);
	$tmp = str_replace('(', '\(', $tmp);
	$tmp = str_replace(')', '\)', $tmp);
	$tmp = str_replace('*', '\*', $tmp);
	return $tmp;
}

if (isset($_GET['firstName']))
	$firstName = sanitize($_GET['firstName']);
else
	$firstName = '';
	
if (isset($_GET['lastName']))
	$lastName = sanitize($_GET['lastName']);
else
	$lastName = '';

if (isset($_GET['extension']))
	$extension = sanitize($_GET['extension']);
else
	$extension = '';
	

// begin easter egg
if ($lastName == 'monkeys') {
	header('Location: monkeys.php');
	exit(0);
}
// end easter egg


if ($firstName or $lastName) {
	$query = '(&';
	
	$query .= '(|(givenName=' . $firstName . '*)';
	$query .= '(displayName=' . $firstName . '*))';
	
	$query .= '(sn=' . $lastName . '*))';
}
else if ($extension) {
	$query .= 'telephoneNumber=*' . $extension;
}
else {
	trigger_error('You must enter a name or four-digit extension.', E_USER_ERROR);
}


$res = ldap_search($ldap, $baseDN, $query);
$entries = ldap_get_entries($ldap, $res);

if ($entries['count'] == 0) {
	trigger_error('Your search returned no results.', E_USER_ERROR);
}
else if ($entries['count'] == 1) {
	header('Location: info.php?uid=' . $entries[0]['uid'][0]);
	exit(0);
}


for ($i = 0; $i < $entries['count']; $i++) {
	$people[$entries[$i]['uid'][0]] = $entries[$i]['sn'][0] . ", "
	                                  . $entries[$i]['givenname'][0];
}

asort($people);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
	<HEAD>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<TITLE>Search Results</TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/directory.css">
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/base.css">
	</HEAD>
	
	<BODY>
		<IMG SRC="gfx/topbar.gif">
		
		<H1>Search Results</H1>
		
		<DIV>
			<P>Found <? print($entries['count']); ?> people. Please select from the list below.
			<UL>
				<?
			
				foreach ($people as $uid => $name) {
					?><LI><A HREF="info.php?uid=<? print($uid); ?>"><? print($name); ?></A><BR><?
					print("\n\t\t\t");
				}
					
				?>
	
			</UL>
		</DIV>
	</BODY>
</HTML>
