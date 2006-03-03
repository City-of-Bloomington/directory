<?php
/*
	Saves a person's info back to LDAP

	$_POST variables:	uid					telephoneNumber
						givenName			facsimileTelephoneNumber
						sn					physicalDeliveryOfficeName
						displayName			mail
						title				jpegPhoto
						businessCategory
						departmentNumber
*/
	verifyUser("Administrator");

	# Go ahead and bind to the LDAP server so we can edit stuff
	ldap_unbind($LDAP_CONNECTION);

	$LDAP_CONNECTION = ldap_connect(LDAP_SERVER);
	ldap_set_option($LDAP_CONNECTION,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_bind($LDAP_CONNECTION,LDAP_USERNAME_ATTRIBUTE."=".LDAP_ADMIN_USER.",o=city.bloomington.in.us",LDAP_ADMIN_PASS) or die(ldap_error($LDAP_CONNECTION));
	#----------------------------------------------------------------------------------------------------
	# Clean all the inputs
	#----------------------------------------------------------------------------------------------------
	$_POST['givenName'] = trim($_POST['givenName']);
	$_POST['sn'] = trim($_POST['sn']);
	$_POST['displayName'] = trim($_POST['displayName']);
	$_POST['title'] = trim($_POST['title']);
	$_POST['telephoneNumber'] = ereg_replace("[^0-9\-]","",$_POST['telephoneNumber']);
	$_POST['facsimileTelephoneNumber'] = ereg_replace("[^0-9\-]","",$_POST['facsimileTelephoneNumber']);
	$_POST['mail'] = trim($_POST['mail']);

	# Make sure we've got the minimum required fields
	if (!$_POST['givenName'] || !$_POST['sn'])
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: editPersonForm.php?uid=$_POST[uid]");
		exit();
	}

	#----------------------------------------------------------------------------------------------------
	# Check for an uploaded photo
	#----------------------------------------------------------------------------------------------------
	if ($_FILES['jpegPhoto']['size'] && is_uploaded_file($_FILES['jpegPhoto']['tmp_name']))
	{
		list($filename,$ext) = explode(".",$_FILES['jpegPhoto']['name']);
		if (strtolower($ext) == "jpg")
		{
			$photo = file_get_contents($_FILES['jpegPhoto']['tmp_name']);
		}
	}


	#----------------------------------------------------------------------------------------------------
	# Find out what attributes we've got in there to start out with.
	# In LDAP, you have to add, modify, or delete attributes as appropriate.
	# You can't modify something that's not there, you have to add it.
	# You can't modify something to be an empty string.  You have to delete it.
	#----------------------------------------------------------------------------------------------------
	$result = ldap_search($LDAP_CONNECTION,LDAP_DN,LDAP_USERNAME_ATTRIBUTE."=".$_POST[LDAP_USERNAME_ATTRIBUTE]);
	$entries = ldap_get_entries($LDAP_CONNECTION, $result);

	#----------------------------------------------------------------------------------------------------
	# Set up to do a modify for existing attributes that we have values for
	#----------------------------------------------------------------------------------------------------
	# These first attributes are required according to LDAP, and should always be there.  If they aren't
	# we'll let PHP error out on it's own
	$modifiedAttributes['givenName'] = $_POST['givenName'];
	$modifiedAttributes['sn'] = $_POST['sn'];
	$modifiedAttributes['businessCategory'] = $_POST['businessCategory'];
	$modifiedAttributes['departmentNumber'] = $_POST['departmentNumber'];
	$modifiedAttributes['physicalDeliveryOfficeName'] = $_POST['physicalDeliveryOfficeName'];

	$addedAttributes = array();
	$deletedAttributes = array();

	# These may or may not be there.  We need to check and do an add if they're not there already
	if ($_POST['title'])
	{
		if (isset($entries[0]['title'])) { $modifiedAttributes['title'] = $_POST['title']; }
		else { $addedAttributes['title'] = $_POST['title']; }
	}

	if ($_POST['displayName'])
	{
		if (isset($entries[0]['displayname'])) { $modifiedAttributes['displayName'] = $_POST['displayName']; }
		else { $addedAttributes['displayName'] = $_POST['displayName']; }
	}
	else
	{
		# We have to do a delete, but only if it's actually there to delete
		if (isset($entries[0]['displayname'])) { $deletedAttributes['displayName'] = array(); }
	}

	if ($_POST['telephoneNumber'])
	{
		if (isset($entries[0]['telephonenumber'])) { $modifiedAttributes['telephoneNumber'] = $_POST['telephoneNumber']; }
		else { $addedAttributes['telephoneNumber'] = $_POST['telephoneNumber']; }
	}
	else
	{
		if (isset($entries[0]['telephonenumber'])) { $deletedAttributes['telephoneNumber'] = array(); }
	}

	if ($_POST['facsimileTelephoneNumber'])
	{
		if (isset($entries[0]['facsimiletelephonenumber'])) { $modifiedAttributes['facsimileTelephoneNumber'] = $_POST['facsimileTelephoneNumber']; }
		else { $addedAttributes['facsimileTelephoneNumber'] = $_POST['facsimileTelephoneNumber']; }
	}
	else
	{
		if (isset($entries[0]['facsimileTelephoneNumber'])) { $deletedAttributes['facsimileTelephoneNumber'] = array(); }
	}

	if ($_POST['mail'])
	{
		if (isset($entries[0]['mail'])) { $modifiedAttributes['mail'] = $_POST['mail']; }
		else { $addedAttributes['mail'] = $_POST['mail']; }
	}
	else
	{
		if (isset($entries[0]['mail'])) { $deletedAttributes['mail'] = array(); }
	}
	if (isset($photo))
	{
		if (isset($entries[0]['jpegphoto'])){ $modifiedAttributes['jpegPhoto'] = $photo; }
		else { $addedAttributes['jpegPhoto'] = $photo; }

		# For now, we don't want to delete a photo just because they didn't submit a new one.
	}


	#----------------------------------------------------------------------------------------------------
	# Put all the data back into LDAP
	#----------------------------------------------------------------------------------------------------
	$dn = LDAP_USERNAME_ATTRIBUTE."=".$_POST[LDAP_USERNAME_ATTRIBUTE].",ou=people,o=city.bloomington.in.us";
	ldap_mod_replace($LDAP_CONNECTION,$dn,$modifiedAttributes) or die(print_r($modifiedAttributes).ldap_error($LDAP_CONNECTION));
	if (count($addedAttributes)) { ldap_mod_add($LDAP_CONNECTION,$dn,$addedAttributes) or die(print_r($addedAttributes).ldap_error($LDAP_CONNECTION)); }
	if (count($deletedAttributes)) { ldap_mod_del($LDAP_CONNECTION,$dn,$deletedAttributes) or die(print_r($deletedAttributes).ldap_error($LDAP_CONNECTION)); }

	Header("Location: viewPerson.php?uid=$_POST[uid]");
?>
