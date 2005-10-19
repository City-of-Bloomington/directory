<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  index.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  Main page for employee directory.
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
		<TITLE>Employee Directory</TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/base.css">
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/directory.css">
	</HEAD>
	
	<BODY ONLOAD="document.searchByName.lastName.focus();">
		<IMG SRC="gfx/topbar.gif">
		
		<H1>Search by Name</H1>
		
		<DIV>
			<P>Enter the name or four-digit extension of the person you wish to locate. You may enter a partial name.
			<P><FORM CLASS="name" NAME="searchByName" ACTION="search.php" METHOD=GET>
				<TABLE CLASS="searchform">
					<TR>
						<TH>Last Name:</TH>
						<TD><INPUT NAME="lastName" SIZE=20></TD>
					</TR>
					<TR>
						<TH>First Name:</TH>
						<TD><INPUT NAME="firstName" SIZE=20></TD>
					</TR>
					<TR>
						<TD CLASS="submit" COLSPAN=2><INPUT TYPE=SUBMIT VALUE="Search"></TD>
					</TR>
				</TABLE>
			</FORM>
		</DIV>
		
		<H1>Search by Number</H1>
		
		<DIV>
			<P>You may also perform a reverse lookup on a four-digit extension.
			<P><FORM CLASS="extension" ACTION="search.php" METHOD=GET>
				<TABLE CLASS="searchform">
					<TR>
						<TH>Extension:</TH>
						<TD><INPUT NAME="extension" SIZE=4></TD>
					</TR>
					<TR>
						<TD CLASS="submit" COLSPAN=2><INPUT TYPE=SUBMIT VALUE="Search"></TD>
					</TR>
				</TABLE>
			</FORM>
		</DIV>
		
		<H1>Main Numbers</H1>
		
		<DIV>
			<P>For a list of main numbers, <A HREF="mains.php">click here</A>.
		</DIV>
	</BODY>
</HTML>
