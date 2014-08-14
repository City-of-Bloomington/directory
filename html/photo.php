<?php
/*
	Gets a user's photo out of LDAP and streams it to the browser as an image

	$_GET variables:	uid
*/
	$user = $adldap->user()->infoCollection($_GET['uid'], array('jpegphoto'));

	Header('Content-type: image/jpeg');
	echo $user->jpegphoto;
?>
