<?php
/*
	Displays information for a single person.

	$_GET variables:	uid - User ID of the person to display.
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
<?php
	require_once(GLOBAL_INCLUDES."/classes/LDAPEntry.inc");
	$user = new LDAPEntry($LDAP_CONNECTION,$_GET['uid']);

	# Choose the name to display
	if ($user->getDisplayName()) { $displayName = $user->getDisplayName(); }
	else { $displayName = "{$user->getFirstname()} {$user->getLastname()}"; }

	# Get their photo, if they've got one
	if ($user->getPhoto()) { $photo = "<img src=\"photo.php?uid=$_GET[uid]\" alt=\"$_GET[uid]\" />"; }
	else { $photo = "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />"; }

	echo "
	<div class=\"breadcrumbs\">
		<a href=\"".BASE_URL."\">Departments</a> &gt;
		<a href=\"viewCategory.php?category={$user->getBusinessCategory()}\">{$user->getBusinessCategory()}</a> &gt;
		<a href=\"viewDepartment.php?category={$user->getBusinessCategory()};department={$user->getDepartment()}\">{$user->getDepartment()}</a> &gt;
		<a href=\"viewLocation.php?category={$user->getBusinessCategory()};department={$user->getDepartment()};location={$user->getOffice()}\">{$user->getOffice()}</a>
	</div>
	<table id=\"details\">
	<tr><th>$photo</th>
		<td><h1>$displayName</h1>
			<h3>{$user->getTitle()}</h3>
			<h3>{$user->getDepartment()}</h3>
			<h3>{$user->getBusinessCategory()}</h3>
		</td></tr>
	<tr><th>Phone</th>
		<td>{$user->getPhone()}</td></tr>
	<tr><th>Fax</th>
		<td>{$user->getFax()}</td></tr>
	<tr><th>Delivery Office</th>
		<td>{$user->getOFfice()}</td></tr>
	<tr><th>Email</th>
		<td><a href=\"mailto:{$user->getEmail()}\">{$user->getEmail()}</a></td></tr>
	</table>
	";

	if (isset($_SESSION['USER']) && $_SERVER['REMOTE_ADDR']==$_SESSION['IP_ADDRESS'])
	{
		echo "<div><button type=\"button\" class=\"editLarge\" onclick=\"document.location.href='editPersonForm.php?uid=$_GET[uid]';\">Edit</button></div>";
	}

?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>