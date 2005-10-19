<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  monkeys.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  This is totally awesome.
#  --
#  --     Parameters:  None.
#  --

include('errors.inc.php');
include('connect.inc.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
	<HEAD>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<TITLE>You asked for it.</TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/base.css">
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/directory.css">
	</HEAD>
	
	<BODY ONLOAD="document.searchByName.lastName.focus();">
		<IMG SRC="gfx/topbar.gif">
		
		<H1>Furious George</H1>
		
		<DIV>
			<IMG SRC="gfx/stab.gif">
		</DIV>
	</BODY>
</HTML>
