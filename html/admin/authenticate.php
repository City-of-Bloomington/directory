<?php
/*
	Logs a user into the system.
	A logged in user will have a $_SESSION['USER_ID']
								$_SESSION['IP_ADDRESS']


	$_POST Variables:	username
						password
*/
	# Clear out any old session
	session_destroy();
	session_start();

	if (!isValidUser($_POST['username'],$_POST['password']))
	{
		# Send them back to the login
		Header("Location: home.php");
		exit();
	}

	if (!userHasApplicationAccess($_POST['username'],"Directory"))
	{
		# Send them back to the login
		Header("Location: home.php");
		exit();
	}

	# They're good to go, log them into the site
	$_SESSION['USERNAME'] = $_POST['username'];
	$_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

	Header("Location: $BASE_URL");
?>
