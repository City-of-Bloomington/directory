<?php
/*
	$_GET variables:	uid
*/
	verifyUser("Administrator");
	if (isset($_GET['uid'])) { $user = new LDAPEntry($_GET['uid']); }
	if (isset($_POST['uid']))
	{
		$user = new LDAPEntry($_POST['uid']);
		foreach($_POST['user'] as $field=>$value)
		{
			$set = 'set'.ucfirst($field);
			$user->$set($value);
		}

		# Check for an uploaded photo.
		# Only change the users photo if a new one is uploaded
		if ($_FILES['photo']['size'] && is_uploaded_file($_FILES['photo']['tmp_name']))
		{
			$user->setPhoto($_FILES['photo']['tmp_name']);
		}

		try
		{
			$user->save();
			Header("Location: viewPerson.php?uid={$user->getUID()}");
			exit();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$breadcrumbs = new Block('breadcrumbs.inc');
	$breadcrumbs->category = $user->getBusinessCategory();
	$breadcrumbs->department = $user->getDepartment();
	$breadcrumbs->location = $user->getOffice();

	$template = new Template();
	$template->blocks[] = $breadcrumbs;
	$template->blocks[] = new Block('people/updatePersonForm.inc',array('user'=>$user));
	$template->render();
?>