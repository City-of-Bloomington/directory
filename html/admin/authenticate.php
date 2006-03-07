<?php
/*
	Logs a user into the system.
	A logged in user will be stored in the session as $_SESSION['USER']
	There should also be a $_SESSION['IP_ADDRESS'] to check for ijacking attacks.

	$_POST Variables:	username
						password
*/
	require_once(APPLICATION_HOME."/classes/User.inc");

	try
	{
		$user = new User($_POST['username']);

		if ($user->authenticate($_POST['password'])) { $user->startNewSession(); }
		else
		{
			$_SESSION['errorMessages'][] = "wrongPassword";
			Header("Location: home.php");
			exit();
		}
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = "unknownUser";
		Header("Location: home.php");
		exit();
	}

	Header("Location: ".BASE_URL);
?>
