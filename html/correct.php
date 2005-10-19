<?php
#  --
#  --    Employee Directory
#  --    ITS Systems Group
#  --    City of Bloomington, IN
#  --
#  --           File:  correct.php
#  --     Maintainer:  Dan Neumeyer <neumeyed@bloomingtonIN.gov>
#  --  Configuration:  connect.inc.php
#  --
#  --    Description:  Makes changes to an LDAP entry.
#  --
#  --     Parameters:
#  --            uid - User ID of the person to modify.
#  --       password - User's password.
#  --      firstName - Legal first name.
#  --       lastName - Last name.
#  --    displayName - Preferred full name.
#  --          title - Job title.
#  --     department - Department name.
#  --  businessCategory - Business category (typically a refinement of the
#  --                     department).
#  --  deliveryOffice - Physical delivery office.
#  --          phone - Phone number.
#  --            fax - Fax number.
#  --

include('errors.inc.php');
include('connect.inc.php');


function validate($value, $name) {
	if ($value == "")
		trigger_error("The \"$name\" field cannot be empty. Use your browser's back button to correct the error and try again.", E_USER_ERROR);
	
	return $value;
}

function process_phone($str) {
	$tmp = preg_replace('/[^0123456789]/', '', $str);
	
	if (strlen($tmp) == 0)
		return $tmp;
		
	if (strlen($tmp) == 4)
		$tmp = "812349$tmp";
	else if (strlen($tmp) == 7)
		$tmp = "812$tmp";
	
	if (strlen($tmp) != 10)
		trigger_error("The \"Phone\" and \"Fax\" fields must be four, seven or ten digits long. You may leave either of these fields empty if you wish. Use your browser's back button to correct the error and try again.", E_USER_ERROR);
	
	return substr($tmp, 0, 3) . '-' . substr($tmp, 3, 3) . '-' . substr($tmp, 6);
}

function delete_attribute($connection, $distName, $attr) {
	$delete[$attr] = array();
	ldap_mod_del($connection, $distName, $delete);
}


// Verify user ID and establish distinguished name.

if ((! isset($_POST['uid'])) or ($_POST['uid'] == '') or (! preg_match('/^[abcdefghijklmnopqrstuvwxyz]{1,8}$/', $_POST['uid'])))
	trigger_error('The user ID field is invalid.', E_USER_ERROR);

$uid = $_POST['uid'];
$dn = 'uid=' . $uid . ',' . $baseDN;


// Check password.

$res = ldap_search($ldap, $baseDN, "uid=$uid");
$entries = ldap_get_entries($ldap, $res);

if (($entries['count'] == 1) and isset($entries[0]['userpassword'])) {
	$stored = $entries[0]['userpassword'][0];
	
	if (substr($stored, 0, 7) == "{crypt}")
		$stored = substr($stored, 7);
	else
		$stored = crypt($stored, substr($stored, 0, 2));

	if (crypt($_POST['password'], substr($stored, 0, 2)) != $stored)
		trigger_error("You entered an incorrect password. Use your browser's back button to correct the error and try again.", E_USER_ERROR);
}
else
	trigger_error("You entered an incorrect password. Use your browser's back button to correct the error and try again.", E_USER_ERROR);


// Set up attribute modifications. Delete empty attributes as they are found.

$entry['givenName'] = validate($_POST['firstName'], 'First Name');
$entry['sn'] = validate($_POST['lastName'], 'Last Name');

if ($_POST['displayName'])
	$entry['displayName'] = $_POST['displayName'];
else
	delete_attribute($ldap, $dn, 'displayName');

$entry['title'] = validate($_POST['title'], 'Title');
$entry['departmentNumber'] = validate($_POST['department'], 'Department');
$entry['businessCategory'] = validate($_POST['businessCategory'], 'Business Category');
$entry['physicalDeliveryOfficeName'] = validate($_POST['deliveryOffice'], 'Delivery Office');

$phone = process_phone($_POST['phone']);
if ($phone)
	$entry['telephoneNumber'] = $phone;
else
	delete_attribute($ldap, $dn, 'telephoneNumber');
	
$fax = process_phone($_POST['fax']);
if ($fax)
	$entry['facsimileTelephoneNumber'] = $fax;
else
	delete_attribute($ldap, $dn, 'facsimileTelephoneNumber');


// Modify attributes and redirect.

ldap_mod_replace($ldap, $dn, $entry);
header("Location: info.php?uid=$uid");

?>
