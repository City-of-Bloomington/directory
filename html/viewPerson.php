<?php
/*
	Displays information for a single person.

	$_GET variables:	uid - User ID of the person to display.
*/
	include("$GLOBAL_INCLUDES/xhtmlHeader.inc");
	include("$APPLICATION_HOME/includes/banner.inc");
	include("$APPLICATION_HOME/includes/menubar.inc");
	include("$APPLICATION_HOME/includes/navigation.inc");
?>
<div id="mainContent">
	<?php
		$result = ldap_search($LDAP_SERVER, $LDAP_DN, "uid=$_GET[uid]");
		$entries = ldap_get_entries($LDAP_SERVER, $result);

		# Choose the name to display
		if (isset($entries[0]['displayname']) and ($entries[0]['displayname'][0])) { $displayName = $entries[0]['displayname'][0]; }
		else { $displayName = "{$entries[0]['givenname'][0]} {$entries[0]['sn'][0]}"; }

		# Get their photo, if they've got one
		if (isset($entries[0]['jpegphoto'])) { $photo = "<img src=\"photo.php?uid=$_GET[uid]\" alt=\"$_GET[uid]\" />"; }
		else { $photo = "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />"; }
		echo "
		<table id=\"details\">
		<tr><th>$photo</th>
			<td><h1>$displayName</h1>
				<h3>{$entries[0]['title'][0]}</h3>
				<h3>{$entries[0]['departmentnumber'][0]}</h3>
				<h3>{$entries[0]['businesscategory'][0]}</h3>
			</td></tr>
		";


		# People may not have all of these entries.  Make sure they have 'em before trying to display 'em
		$telephonenumber = isset($entries[0]['telephonenumber'][0]) ? $entries[0]['telephonenumber'][0] : "";
		$facsimiletelephonenumber = isset($entries[0]['facsimiletelephonenumber'][0]) ? $entries[0]['facsimiletelephonenumber'][0] : "";
		$physicaldeliveryofficename = isset($entries[0]['physicaldeliveryofficename'][0]) ? $entries[0]['physicaldeliveryofficename'][0] : "";
		$mail = isset($entries[0]['mail'][0]) ? $entries[0]['mail'][0] : "";
		echo "
		<tr><th>Phone</th>
			<td>$telephonenumber</td></tr>
		<tr><th>Fax</th>
			<td>$facsimiletelephonenumber</td></tr>
		<tr><th>Delivery Office</th>
			<td>$physicaldeliveryofficename</td></tr>
		<tr><th>Email</th>
			<td><a href=\"mailto:$mail\">$mail</a></td></tr>
		</table>
		";
	?>
</div>
<?php
	include("$APPLICATION_HOME/includes/footer.inc");
	include("$GLOBAL_INCLUDES/xhtmlFooter.inc");
?>
