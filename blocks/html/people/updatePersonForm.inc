<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->user
*/
	# Get their photo, if they've got one
	if ($this->user->getPhoto()) { $photo = "<img src=\"photo.php?uid={$this->user->getUID()}\" alt=\"{$this->user->getUID()}\" />"; }
	else { $photo = "<img src=\"images/nophoto.jpg\" alt=\"No Photo\" />"; }

	$departments = array("Bloomington Transit",
							"Accounting","City Clerk","Community and Family Resources","Controller","Council Office","Employee Services",
								"HAND","ITS","Legal","Office of the Mayor","Parks and Recreation","Planning","Public Works","Risk Management",
							"Administration","Fire",
							"Detectives","Records","Uniformed Officers","Dispatch",
							"Stonebelt",
							"Blucher Poole","Communication","Customer Services","Dillman","Monroe","Purchasing","T&amp;D");

	$businessCategories = array("Bloomington Transit","City Hall","Fire","Police","Utilities");

	$offices = array("Bloomington Transit","Showers","BACC","Banneker Center","Cascades Golf Course","Frank Southern Center","Juke Box Community Building","Twin Lakes",
						"Animal Shelter","Engineering","Facilities","Fleet Maintenance","Parking Enforcement","Sanitation","Street","Traffic",
						"Fire HQ","Fire Administration","Fire Station 1","Fire Station 2","Fire Station 3","Fire Station 4","Fire Station 5",
						"Police HQ",
						"Rosehill","Adams Street",
						"Dillman","Stonebelt","Blucher Poole","Utilities Service Center","Monroe");
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<fieldset><legend>Edit Details</legend>
	<input name="uid" type="hidden" value="<?php echo $this->user->getUID(); ?>" />
	<table id="details">
	<tr><td><table>
			<tr><td><?php echo $photo; ?></td></tr>
			<tr><td><input name="photo" type="file" /></td></tr>
			</table>
		</td>
		<td><table>
			<tr><td><label for="user-firstname">Firstname</label></td>
				<td><input name="user[firstname]" id="user-firstname" value="<?php echo $this->user->getFirstname(); ?>" /></td></tr>
			<tr><td><label for="user-lastname">Lastname</label></td>
				<td><input name="user[lastname]" id="user-lastname" value="<?php echo $this->user->getLastname(); ?>" /></td></tr>
			<tr><td><label for="user-displayName">Display Name</label></td>
				<td><input name="user[displayName]" id="user-displayName" value="<?php echo $this->user->getDisplayName(); ?>" /></td></tr>
			<tr><td><label for="user-title">Title</label></td>
				<td><input name="user[title]" id="user-title" value="<?php echo $this->user->getTitle(); ?>" /></td></tr>
			<tr><td><label for="user-businessCategory">Business Category</label></td>
				<td><select name="user[businessCategory]" id="user-businessCategory">
					<?php
						foreach($businessCategories as $category)
						{
							if ($category == $this->user->getBusinessCategory()) { echo "<option selected=\"selected\">$category</option>"; }
							else { echo "<option>$category</option>"; }
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td><label for="user-department">Department</label></td>
				<td><select name="user[department]" id="user-department">
					<?php
						foreach($departments as $department)
						{
							if ($this->user->getDepartment() == $department) { echo "<option selected=\"selected\">$department</option>"; }
							else { echo "<option>$department</option>"; }
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td><label for="user-phone">Phone</label></td>
				<td><input name="user[phone]" id="user-phone" value="<?php echo $this->user->getPhone(); ?>" /></td></tr>
			<tr><td><label for="user-preferredPhone">Preferred Phone</label></td>
				<td><input name="user[preferredPhone]" id="user-preferredPhone" value="<?php echo $this->user->getPreferredPhone(); ?>" /></td></tr>
			<tr><td><label for="user-webPhone">Web Phone</label></td>
				<td><input name="user[webPhone]" id="user-webPhone" value="<?php echo $this->user->getWebPhone(); ?>" /></td></tr>
			<tr><td><label for="user-fax">Fax</label></td>
				<td><input name="user[fax]" id="user-fax" value="<?php echo $this->user->getFax(); ?>" /></td></tr>
			<tr><td><label for="user-mobile">Mobile</label></td>
				<td><input name="user[mobile]" id="user-mobile" value="<?php echo View::escape($this->user->getMobile()); ?>" />
			<tr><td><label for="user-office">Delivery Office</label></td>
				<td><select name="user[office]" id="user-office">
					<?php
						foreach($offices as $office)
						{
							if ($office == $this->user->getOffice()) { echo "<option selected=\"selected\">$office</option>"; }
							else { echo "<option>$office</option>"; }
						}
						?>
						</select>
				</td>
			</tr>
			<tr><td><label for="user-email">Email</label></td>
				<td><input name="user[email]" id="user-email" value="<?php echo $this->user->getEmail(); ?>" /></td></tr>
			</table>
		</td>
	</tr>
	</table>

	<button type="submit" class="save">Save</button>
	<button type="button" class="cancel" onclick="document.location.href='viewPerson.php?uid=<?php echo $this->user->getUID(); ?>';">Cancel</button>
</fieldset>
</form>