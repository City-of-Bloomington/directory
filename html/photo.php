<?php
/*
	Gets a user's photo out of LDAP and streams it to the browser as an image

	$_GET variables:	uid
*/
	require_once(GLOBAL_INCLUDES."/classes/LDAPEntry.inc");
	$user = new LDAPEntry($LDAP_CONNECTION,$_GET['uid']);

	Header('Content-type: image/jpeg');
	echo $user->getPhoto();
?>