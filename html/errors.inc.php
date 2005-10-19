<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  errors.inc.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --
#  --    Description:  Sets up error handling.
#  --
#  --     Parameters:  None.
#  --

function error_handler($code, $description, $file, $line) {
	$basename = basename($file);

	switch ($code) {
	case E_USER_ERROR:
		error_log("$basename ($line) FATAL: $description");
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
	<HEAD>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<TITLE>Error</TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/directory.css">
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/base.css">
	</HEAD>
	
	<BODY>
		<IMG SRC="gfx/topbar.gif">
	
		<H1>Sorry</H1>
		<DIV>
			<P><? print($description); ?>
		</DIV>
	</BODY>
</HTML>
		<?
		
		exit();
		break;
	
	default:
		error_log("$basename ($line): $description");
		break;
	}
}

error_reporting(E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
set_error_handler(error_handler);

?>
