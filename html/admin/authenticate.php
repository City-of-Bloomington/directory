<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/**
 *	Logs a user into the system.
 *	A logged in user will have a $_SESSION['USER']
 *								$_SESSION['IP_ADDRESS']
 *								$_SESSION['APPLICATION_NAME']
 *
 *
 *	$_POST Variables:	username
 *						password
 *						returnURL
 */
	try
	{
		if ($adldap->user()->authenticate($_POST['username'], $_POST['password'])) 
		{
			$user = new User($_POST['username']);
			$user->startNewSession();
		}
		else { throw new Exception("wrongPassword"); }
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: ".BASE_URL);
		exit();
	}

	Header("Location: ".BASE_URL);
?>
