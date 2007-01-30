<?php
/*
	Gets a user's photo out of LDAP and streams it to the browser as an image

	$_GET variables:	uid
*/
	$user = new LDAPEntry($_GET['uid']);

	Header('Content-type: image/jpeg');
	echo $user->getPhoto();
?>