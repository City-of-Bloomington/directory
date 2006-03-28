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
	ldap_bind($LDAP_CONNECTION,LDAP_USERNAME_ATTRIBUTE."=".LDAP_ADMIN_USER.",o=".LDAP_DOMAIN,LDAP_ADMIN_PASS) or die(ldap_error($LDAP_CONNECTION));

	require_once(GLOBAL_INCLUDES."/classes/LDAPEntry.inc");
	$user = new LDAPEntry($LDAP_CONNECTION,$_POST['uid']);



	$user->setFirstname($_POST['givenName']);
	$user->setLastname($_POST['sn']);
	$user->setBusinessCategory($_POST['businessCategory']);
	$user->setDepartment($_POST['departmentNumber']);
	$user->setOffice($_POST['physicalDeliveryOfficeName']);
	$user->setTitle($_POST['title']);
	$user->setDisplayName($_POST['displayName']);
	$user->setPhone($_POST['telephoneNumber']);
	$user->setFax($_POST['facsimileTelephoneNumber']);
	$user->setEmail($_POST['mail']);

	#----------------------------------------------------------------------------------------------------
	# Check for an uploaded photo.  Only change the users photo if a new one is uploaded
	#----------------------------------------------------------------------------------------------------
	if ($_FILES['jpegPhoto']['size'] && is_uploaded_file($_FILES['jpegPhoto']['tmp_name']))
	{
		$user->setPhoto($_FILES['jpegPhoto']['tmp_name']);
	}

	#----------------------------------------------------------------------------------------------------
	# Make sure we've got the minimum required fields
	#----------------------------------------------------------------------------------------------------
	if (!$user->getFirstname() || !$user->getLastname())
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: editPersonForm.php?uid=$_POST[uid]");
		exit();
	}

	print_r($user);


	#Header("Location: viewPerson.php?uid=$_POST[uid]");
?>