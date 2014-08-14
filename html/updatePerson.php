<?php
/*
	$_GET variables:	uid
*/
	verifyUser("Administrator");
	if (isset($_GET['uid'])) { $user = $adldap->user()->infoCollection($_GET['uid'], array('*')); }
	if (isset($_POST['uid']))
	{
		//$user = new LDAPEntry($_POST['uid']);
		$mod = array();
		foreach($_POST['user'] as $field=>$value) 
		{ 
			ucfirst($field); 
			if (!$value) { $mod[$field] = array(); }
			else { $mod[$field] = $value; }
		}

		try {
			$modify = $adldap->user()->modify($_POST['uid'], $mod); 
		}

		catch (adLDAPException $e) {
			echo $e; exit();
		}

		# Check for an uploaded photo.
		# Only change the users photo if a new one is uploaded
		if ($_FILES['photo']['size'] && is_uploaded_file($_FILES['photo']['tmp_name']))
		{
			$file_data = file_get_contents($_FILES['photo']['tmp_name']);
			try {
				$picture_modified = $adldap->user()->modify($_POST['uid'], array("photo" => $file_data));
			}
			catch (adLDAPException $e) {
				echo $e; exit();
			}	
		}


		Header("Location: viewPerson.php?uid={$_POST['uid']}");
		exit();
	}

	$breadcrumbs = new Block('breadcrumbs.inc');
	$breadcrumbs->category = $user->businesscategory;
	$breadcrumbs->department = $user->department;
	$breadcrumbs->location = $user->physicalofficename;

	$template = new Template();
	$template->blocks[] = $breadcrumbs;
	$template->blocks[] = new Block('people/updatePersonForm.inc',array('user'=>$user));
	$template->render();
?>
