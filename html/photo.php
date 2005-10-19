<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  photo.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  Retrieves a photo for a single person.
#  --
#  --     Parameters:
#  --            uid - User ID of the person whose photo should be retrieved.
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

$jpegPhotos = ldap_get_values_len($ldap, ldap_first_entry($ldap, $res), 'jpegphoto');

header('Content-type: image/jpeg');
print($jpegPhotos[0]);
?>