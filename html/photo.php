<?php
/*
	Gets a user's photo out of LDAP and streams it to the browser as an image

	$_GET variables:	uid
*/
	$result = ldap_search($LDAP_CONNECTION, LDAP_DN, LDAP_USERNAME_ATTRIBUTE."=$_GET[uid]");
	$entries = ldap_get_entries($LDAP_CONNECTION, $result);


	$jpegPhotos = ldap_get_values_len($LDAP_CONNECTION, ldap_first_entry($LDAP_CONNECTION, $result), 'jpegphoto');

	Header('Content-type: image/jpeg');
	print($jpegPhotos[0]);
?>