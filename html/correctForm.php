<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  correctForm.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --                  departments.inc.php
#  --
#  --    Description:  Form for making corrections to LDAP entries.
#  --
#  --     Parameters:
#  --            uid - User ID of the person to correct.
#  --

include('errors.inc.php');
include('connect.inc.php');
include('departments.inc.php');

function sanitize($str) {
	$tmp = str_replace('\\', '\\\\', $str);
	$tmp = str_replace('(', '\(', $tmp);
	$tmp = str_replace(')', '\)', $tmp);
	$tmp = str_replace('*', '\*', $tmp);
	return $tmp;
}

$uid = sanitize($_GET['uid']);


if (! $uid) {
	trigger_error('You must specify a user ID.', E_USER_ERROR);
}


$res = ldap_search($ldap, $baseDN, "uid=$uid");
$entries = ldap_get_entries($ldap, $res);

if ($entries['count'] == 0) {
	trigger_error('Your search returned no results.', E_USER_ERROR);
}


if (isset($entries[0]['displayname']) and ($entries[0]['displayname'][0])) {
	$displayName = $entries[0]['displayname'][0];
}
else {
	$displayName = $entries[0]['givenname'][0] . " " . $entries[0]['sn'][0];
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
	<HEAD>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<TITLE>Corrections for <? print($displayName); ?></TITLE>
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/base.css">
		<LINK REL=STYLESHEET TYPE="text/css" HREF="style/directory.css">
	</HEAD>
	
	<BODY ONLOAD="document.correctForm.password.focus();">
		<IMG SRC="gfx/topbar.gif">
		
		<H1>Corrections for <? print($displayName); ?></H1>
		
		<DIV>
			<P>You must enter your password to make changes to your contact information.
			
			<P><FORM NAME="correctForm" ACTION="correct.php" METHOD=POST>
				<INPUT TYPE=HIDDEN NAME="uid" VALUE="<? print($uid); ?>">
				<TABLE>
					<TR>
						<TH><SPAN CLASS="red">*</SPAN>Password:</TH>
						<TD><INPUT TYPE=PASSWORD NAME="password" SIZE=8></TD>
					</TR>
					<TR>
						<TD COLSPAN=2>&nbsp;</TD>
					</TR>
					<TR>
						<TH CLASS="multiline"><SPAN CLASS="red">*</SPAN>Legal First Name:</TH>
						<TD>
							<INPUT NAME="firstName" VALUE="<? print($entries[0]['givenname'][0]); ?>" SIZE=20><BR>
							<SPAN CLASS="note">Please don't enter short names or nicknames in this field.<BR>
							You may enter a short name in the &quot;Preferred Full Name&quot; field below.</SPAN>
						</TD>
					</TR>
					<TR>
						<TD COLSPAN=2>&nbsp;</TD>
					</TR>
					<TR>
						<TH><SPAN CLASS="red">*</SPAN>Last Name:</TH>
						<TD><INPUT NAME="lastName" VALUE="<? print($entries[0]['sn'][0]); ?>" SIZE=20></TD>
					</TR>
					<TR>
						<TH>Preferred Full Name:</TH>
						<TD><INPUT NAME="displayName" VALUE="<? if (isset($entries[0]['displayname'])) print($entries[0]['displayname'][0]); ?>" SIZE=20></TD>
					</TR>
					<TR>
						<TD COLSPAN=2>&nbsp;</TD>
					</TR>
					<TR>
						<TH><SPAN CLASS="red">*</SPAN>Title:</TH>
						<TD><INPUT NAME="title" VALUE="<? print($entries[0]['title'][0]); ?>" SIZE=50></TD>
					</TR>
					<TR>
						<TH><SPAN CLASS="red">*</SPAN>Department:</TH>
						<TD>
							<SELECT NAME="department"><?
						
							foreach ($departments as $dept) {
								print("\n\t\t\t\t\t\t\t");
								
								?><OPTION VALUE="<? print($dept); ?>"<?
								if ($dept == $entries[0]['departmentnumber'][0]) {
									?> SELECTED<?
								}
								?>><? print($dept); ?></OPTION><?
							}
						
						?>
							</SELECT>
							<SELECT NAME="businessCategory"><?
						
							foreach ($businessCategories as $bc) {
								print("\n\t\t\t\t\t\t\t");
								
								?><OPTION VALUE="<? print($bc); ?>"<?
								if ($bc == $entries[0]['businesscategory'][0]) {
									?> SELECTED<?
								}
								?>><? print($bc); ?></OPTION><?
							}
						
						?>
							</SELECT>
						</TD>
					</TR>
					<TR>
						<TH><SPAN CLASS="red">*</SPAN>Delivery Office:</TH>
						<TD>
							<SELECT NAME="deliveryOffice"><?
						
							foreach ($deliveryOffices as $delivery) {
								print("\n\t\t\t\t\t\t\t");
								
								?><OPTION VALUE="<? print($delivery); ?>"<?
								if ($delivery == $entries[0]['physicaldeliveryofficename'][0]) {
									?> SELECTED<?
								}
								?>><? print($delivery); ?></OPTION><?
							}
						
						?>
							</SELECT>
						</TD>
					</TR>
					<TR>
						<TD COLSPAN=2>&nbsp;</TD>
					</TR>
					<TR>
						<TH>Phone:</TH>
						<TD><INPUT NAME="phone" VALUE="<? if (isset($entries[0]['telephonenumber'])) print($entries[0]['telephonenumber'][0]); ?>" SIZE=12></TD>
					</TR>
					<TR>
						<TH>Fax:</TH>
						<TD><INPUT NAME="fax" VALUE="<? if (isset($entries[0]['facsimiletelephonenumber'])) print($entries[0]['facsimiletelephonenumber'][0]); ?>" SIZE=12></TD>
					</TR>
					<TR>
						<TD COLSPAN=2>&nbsp;</TD>
					</TR>
					<TR>
						<TD>&nbsp;</TD>
						<TD><INPUT TYPE=SUBMIT VALUE="Submit"></TD>
					</TR>
				</TABLE>
			</FORM>
			
			<P><SPAN CLASS="red">*</SPAN>Required fields
		</DIV>
	</BODY>
</HTML>
