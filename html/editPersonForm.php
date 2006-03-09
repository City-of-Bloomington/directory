<?php
/*
	Lets you edit information for a single person.

	$_GET variables:	uid - User ID of the person to display.
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
<?php
	include(GLOBAL_INCLUDES."/errorMessages.inc");

	$result = ldap_search($LDAP_CONNECTION,LDAP_DN,LDAP_USERNAME_ATTRIBUTE."=$_GET[uid]");
	$entries = ldap_get_entries($LDAP_CONNECTION, $result);

	# Get their photo, if they've got one
	if (isset($entries[0]['jpegphoto'])) { $photo = "<img src=\"photo.php?uid=$_GET[uid]\" alt=\"$_GET[uid]\" />"; }
	else { $photo = "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />"; }

	# Get the details out of LDAP that we're going to use
	$givenName = htmlspecialchars($entries[0]['givenname'][0],ENT_QUOTES);
	$sn = htmlspecialchars($entries[0]['sn'][0],ENT_QUOTES);
	$businessCategory = htmlspecialchars($entries[0]['businesscategory'][0],ENT_QUOTES);
	$departmentNumber = htmlspecialchars($entries[0]['departmentnumber'][0],ENT_QUOTES);
	$physicalDeliveryOfficeName = htmlspecialchars($entries[0]['physicaldeliveryofficename'][0],ENT_QUOTES);

	# People may not have all of these entries.  Make sure they have 'em before trying to display 'em
	$title = isset($entries[0]['title'][0]) ? htmlspecialchars($entries[0]['title'][0],ENT_QUOTES) : "";
	$displayName = isset($entries[0]['displayname'][0]) ? htmlspecialchars($entries[0]['displayname'][0],ENT_QUOTES) : "";
	$telephoneNumber = isset($entries[0]['telephonenumber'][0]) ? $entries[0]['telephonenumber'][0] : "";
	$facsimileTelephoneNumber = isset($entries[0]['facsimiletelephonenumber'][0]) ? $entries[0]['facsimiletelephonenumber'][0] : "";
	$physicalDeliveryOfficeName = isset($entries[0]['physicaldeliveryofficename'][0]) ? $entries[0]['physicaldeliveryofficename'][0] : "";
	$mail = isset($entries[0]['mail'][0]) ? $entries[0]['mail'][0] : "";


	$departments = array("Bloomington Transit",
							"Accounting","City Clerk","Community and Family Resources","Controller","Council Office","Employee Services","Engineering",
								"HAND","ITS","Legal","Mayor&#039;s Office","Parking Enforcement","Parks and Recreation","Planning","Public Works","Risk Management",
							"Administration","Fire",
							"Detectives","Records","Uniformed Officers","Dispatch",
							"Stonebelt",
							"Blucher Poole","Communication","Customer Services","Dillman","Monroe","Purchasing","T&amp;D");

	$businessCategories = array("Bloomington Transit","City Hall","Fire","Police","Stonebelt","Utilities");

	$offices = array("Bloomington Transit","Showers","BACC","Banneker Center","Cascades Golf Course","Frank Southern Center","Juke Box Community Building","Twin Lakes",
						"Animal Shelter","Fleet Maintenance","Sanitation","Street","Traffic",
						"Fire HQ","Fire Administration","Fire Station 1","Fire Station 2","Fire Station 3","Fire Station 4","Fire Station 5",
						"Police HQ",
						"Dillman","Stonebelt","Blucher Poole","Utilities Service Center","Monroe");

	echo "
	<div class=\"breadcrumbs\">
		<a href=\"{BASE_URL}\">Departments</a> >
		<a href=\"viewCategory.php?category={$entries[0]['businesscategory'][0]}\">{$entries[0]['businesscategory'][0]}</a> >
		<a href=\"viewDepartment.php?category={$entries[0]['businesscategory'][0]};department={$entries[0]['departmentnumber'][0]}\">{$entries[0]['departmentnumber'][0]}</a> >
		<a href=\"viewLocation.php?category={$entries[0]['businesscategory'][0]};department={$entries[0]['departmentnumber'][0]};location={$entries[0]['physicaldeliveryofficename'][0]}\">{$entries[0]['physicaldeliveryofficename'][0]}</a>
	</div>
	";


?>
	<form method="post" action="updatePerson.php" enctype="multipart/form-data">
	<fieldset><legend>Edit Details</legend>
		<input name="uid" type="hidden" value="<?php echo $_GET['uid']; ?>" />
		<table id="details">
		<tr><td><table>
				<tr><td><?php echo $photo; ?></td></tr>
				<tr><td><input name="jpegPhoto" type="file" /></td></tr>
				</table>
			</td>
			<td><table>
				<tr><td><label for="givenName">Firstname</label></td>
					<td><input name="givenName" id="givenName" value="<?php echo $givenName ?>" /></td></tr>
				<tr><td><label for="sn">Lastname</label></td>
					<td><input name="sn" id="sn" value="<?php echo $sn ?>" /></td></tr>
				<tr><td><label for="displayName">Display Name</label></td>
					<td><input name="displayName" id="displayName" value="<?php echo $displayName; ?>" /></td></tr>
				<tr><td><label for="title">Title</label></td>
					<td><input name="title" id="title" value="<?php echo $title ?>" /></td></tr>
				<tr><td><label for="businessCategory">Business Category</label></td>
					<td><select name="businessCategory" id="businessCategory">
						<?php
							foreach($businessCategories as $category)
							{
								if ($category == $businessCategory) { echo "<option selected=\"selected\">$category</option>"; }
								else { echo "<option>$category</option>"; }
							}
						?>
						</select>
					</td>
				</tr>
				<tr><td><label for="departmentNumber">Department</label></td>
					<td><select name="departmentNumber" id="departmentNumber">
						<?php
							foreach($departments as $department)
							{
								if ($departmentNumber == $department) { echo "<option selected=\"selected\">$department</option>"; }
								else { echo "<option>$department</option>"; }
							}
						?>
						</select>
					</td>
				</tr>
				<tr><td><label for="telephoneNumber">Phone</label></td>
					<td><input name="telephoneNumber" id="telephoneNumber" value="<?php echo $telephoneNumber; ?>" /></td></tr>
				<tr><td><label for="facsimileTelephoneNumber">Fax</label></td>
					<td><input name="facsimileTelephoneNumber" id="facsimileTelephoneNumber" value="<?php echo $facsimileTelephoneNumber; ?>" /></td></tr>
				<tr><td><label for="physicalDeliveryOfficeName">Delivery Office</label></td>
					<td><select name="physicalDeliveryOfficeName" id="physicalDeliveryOfficeName">
						<?php
						 	foreach($offices as $office)
						 	{
						 		if ($office == $physicalDeliveryOfficeName) { echo "<option selected=\"selected\">$office</option>"; }
						 		else { echo "<option>$office</option>"; }
						 	}
						 ?>
						 </select>
					</td>
				</tr>
				<tr><td><label for="mail">Email</label></td>
					<td><input name="mail" id="mail" value="<?php echo $mail; ?>" /></td></tr>
				</table>
			</td>
		</tr>
		</table>

		<button type="submit" class="save">Save</button>
		<button type="button" class="cancel" onclick="document.location.href='viewPerson.php?uid=<?php echo $_GET['uid']; ?>';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
