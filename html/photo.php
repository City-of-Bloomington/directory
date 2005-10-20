<?php
/*
	Gets a user's photo out of LDAP and streams it to the browser as an image

	$_GET variables:	uid
*/
	$result = ldap_search($LDAP_SERVER, $LDAP_DN, "uid=$_GET[uid]");
	$entries = ldap_get_entries($LDAP_SERVER, $result);


	$jpegPhotos = ldap_get_values_len($LDAP_SERVER, ldap_first_entry($LDAP_SERVER, $result), 'jpegphoto');

	Header('Content-type: image/jpeg');
	print($jpegPhotos[0]);
?>