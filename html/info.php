<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  info.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  Displays information for a single person.
#  --
#  --     Parameters:
#  --            uid - User ID of the person to display.
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

$uid = sanitize($_GET['uid']);


if (! $uid) {
	trigger_error('You must specify a user ID.', E_USER_ERROR);
}


$res = ldap_search($ldap, $baseDN, "uid=$uid");
$entries = ldap_get_entries($ldap, $res);

if ($entries['count'] == 0) {
	trigger_error('Your search returned no results.', E_USER_ERROR);
}


if (isset($entries[0]['displayname']) and ($entries[0]['displayname'][0])) {
	$displayName = $entries[0]['displayname'][0];
}
else {
	$displayName = $entries[0]['givenname'][0] . " " . $entries[0]['sn'][0];
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
	<HEAD>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<TITLE><? print($displayName); ?></TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style.css">
	</HEAD>
	
	<BODY>
		<div><IMG SRC="gfx/topbar.gif"></div>
		
		<div id="photo">
			<? if (isset($entries[0]['jpegphoto'])) { ?>
			<IMG SRC="photo.php?uid=<? print($uid); ?>">
			<? } else { ?>
			<IMG SRC="gfx/nophoto.jpg">
			<? } ?>
		</div>
		
		<h1><? print($displayName); ?></h1>
		
		<div id="position">
			<? print($entries[0]['title'][0]); ?><br>
			<? print($entries[0]['departmentnumber'][0]); ?><br>
			<? print($entries[0]['businesscategory'][0]); ?>
		</div>
		
		<div id="details">
			<table>
				<tr>
					<th>Phone</th>
					<td><? if (isset($entries[0]['telephonenumber'])) print($entries[0]['telephonenumber'][0]); ?></td>
				</tr>
				<tr>
					<th>Fax</th>
					<td><? if (isset($entries[0]['facsimiletelephonenumber'])) print($entries[0]['facsimiletelephonenumber'][0]); ?></td>
				</tr>
				
				<tr><td colspan="2" class="spacer">&nbsp;</td></tr>
				
				<tr>
					<th>Delivery Office</th>
					<td><? print($entries[0]['physicaldeliveryofficename'][0]); ?></td>
				</tr>

				<tr><td colspan="2" class="spacer">&nbsp;</td></tr>
				
				<tr>
					<th>Email</th>
					<td><a href="mailto:<? print($entries[0]['mail'][0]); ?>"><? print($entries[0]['mail'][0]); ?></a></td>
				</tr>
			</table>
		</div>
		
		<div id="footer">
			<a href="./">Search again</a>
			<p>Email corrections to <a href="mailto:helpdesk@bloomington.in.gov?subject=Directory%20correction">helpdesk@bloomington.in.gov</a>.
		</div>
	</body>
</html>
