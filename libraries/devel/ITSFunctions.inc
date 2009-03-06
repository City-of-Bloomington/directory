<?php
/*
	Global, shared functions for all PHP web applications
*/
	#----------------------------------------------------------------------------
	# Load classes on the fly as needed
	#----------------------------------------------------------------------------
	function __autoload($class)
	{
		if (file_exists(APPLICATION_HOME."/classes/$class.inc")) { require_once(APPLICATION_HOME."/classes/$class.inc"); }
		elseif (file_exists(FRAMEWORK."/classes/$class.inc")) { require_once(FRAMEWORK."/classes/$class.inc"); }
	}


	#----------------------------------------------------------------------------
	# We're setting up the servers to provide nicely formatted error messages when PHP bombs out.
	#----------------------------------------------------------------------------
	function prettyPrintErrorMessages ($errno, $errstr, $errfile, $errline)
	{
			echo "
			<div id=\"errorMessages\">
			<p><em>from ".ADMINISTRATOR_NAME.":</em>  There is an error in the code on this page that is through no fault of your own.
					Errors of this sort need to be fixed immediately, though.
					Please help me out by copying and pasting the following error message into an email and sending it to me at
					<a href=\"mailto:".ADMINISTRATOR_EMAIL."\">".ADMINISTRATOR_EMAIL."</a>.</p>
			<p><strong>Code Error:</strong>  Error on line $errline of file $errfile:</p><p>$errstr</p>
			</div>
			";
	}
	set_error_handler("prettyPrintErrorMessages");

	# Object oriented exceptions are handled differently from other PHP errors.
	function exception_handler($exception)
	{
			echo "
			<div id=\"errorMessages\">
			<p><em>from ".ADMINISTRATOR_NAME.":</em>  There is an error in the code on this page that is through no fault of your own.
					Errors of this sort need to be fixed immediately, though.
					Please help me out by copying and pasting the following error message into an email and sending it to me at
					<a href=\"mailto:".ADMINISTRATOR_EMAIL."\">".ADMINISTRATOR_EMAIL."</a>.</p>
			<p><strong>Uncaught exception:</strong>  Exception on line {$exception->getLine()} of file {$exception->getFile()}:</p><p>{$exception->getMessage()}</p>
			</div>
			";
	}
	set_exception_handler('exception_handler');



	function verifyUser($roles=null)
	{
		# Make sure they're logged in
		if (!isset($_SESSION['USER']) || $_SESSION['IP_ADDRESS']!=$_SERVER['REMOTE_ADDR'])
		{
			# They're not logged in.  Boot them out to the login page
			$_SESSION['errorMessages'][] = new Exception("notLoggedIn");
			Header("Location: ".BASE_URL);
			exit();
		}

		# Handle sessions from other applications
		if ($_SESSION['APPLICATION_NAME'] != APPLICATION_NAME) { $_SESSION['USER']->startNewSession(); }

		# Check their roles against the required roles for the page
		if ($roles)
		{
			if (!$_SESSION['USER']->hasRole($roles))
			{
				$_SESSION['errorMessages'][] = new Exception("noAccessAllowed");
				Header("Location: ".BASE_URL);
				#print_r($validTypes);
				#print_r($userRoles);
				exit();
			}
		}
	}

	function userHasRole($roles)
	{
		return (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole($roles));
	}

	/**
	*	Browsers still use & when creating the url's when posting a form.
	* This will convert those into XHTML-compliant semicolons for using inside the markup
	*/
	function getCurrentURL() { return strtr($_SERVER['REQUEST_URI'],"&",";"); }


	function isValidDate($date)
	{
		if (!preg_match("/\d{4}\-\d{1,2}\-\d{{1,2}/",$date)) { return false; }

		$date = explode("-",$date);
		if (count($date) != 3) { return false; }
		if (strlen($date[0]) != 4) { return false; }
		if (1>=$date[1] || $date[1]>=12) { return false; }
		if (1>=$date[2] || $date[2]>=31) { return false; }

		return true;
	}
?>