<?php
/*
	Lists all the people in a category

	$_GET variables:	category
*/
	include("$GLOBAL_INCLUDES/xhtmlHeader.inc");
	include("$APPLICATION_HOME/includes/banner.inc");
	include("$APPLICATION_HOME/includes/menubar.inc");
?>
<div id="mainContent">
<?php
	echo "
	<h3><a href=\"$BASE_URL\">Departments</a> - <a href=\"viewCategory.php?category=$_GET[category]\">$_GET[category]</a></h3>

	<ul>
	";
	#----------------------------------------------------------------------------------------------------------
	# Get all the departments in this category
	#----------------------------------------------------------------------------------------------------------
	$departmentResults = ldap_search($LDAP_SERVER,$LDAP_DN,"businessCategory=$_GET[category]",array("departmentNumber"));
	$departmentEntries = ldap_get_entries($LDAP_SERVER,$departmentResults);

	$departments = array();
	for($i=0; $i<$departmentEntries['count']; $i++)
	{
		if(isset($departmentEntries[$i]['departmentnumber'][0]) && !in_array($departmentEntries[$i]['departmentnumber'][0],$departments)) { $departments[] = $departmentEntries[$i]['departmentnumber'][0]; }
	}
	asort($departments);
	foreach($departments as $department)
	{
		#----------------------------------------------------------------------------------------------------------
		# Get all the deliveryOffices in this department
		#----------------------------------------------------------------------------------------------------------
		echo "<li><a href=\"viewDepartment.php?category=$_GET[category];department=$department\">$department</a><ul>";
		$officeResults = ldap_search($LDAP_SERVER,$LDAP_DN,"(&(businessCategory=$_GET[category])(departmentNumber=$department))",array("physicalDeliveryOfficeName"));
		$officeEntries = ldap_get_entries($LDAP_SERVER,$officeResults);

		$offices = array();
		for($i=0; $i<$officeEntries['count']; $i++)
		{
			if(isset($officeEntries[$i]['physicaldeliveryofficename'][0]) && !in_array($officeEntries[$i]['physicaldeliveryofficename'][0],$offices)) { $offices[] = $officeEntries[$i]['physicaldeliveryofficename'][0]; }
		}
		asort($offices);
		foreach($offices as $office)
		{
			#----------------------------------------------------------------------------------------------------------
			# Do the search
			#----------------------------------------------------------------------------------------------------------
			echo "<li><a href=\"viewLocation.php?category=$_GET[category];department=$department;location=$office\">$office</a><table>";
			$query = "(&(businessCategory=$_GET[category])(departmentNumber=$department)(physicalDeliveryOfficeName=$office))";
			$searchResults = ldap_search($LDAP_SERVER, $LDAP_DN, $query);
			$entries = ldap_get_entries($LDAP_SERVER, $searchResults);
			$people = array();

			for ($i = 0; $i < $entries['count']; $i++)
			{
				$uid = $entries[$i]['uid'][0];
				$people[$uid] = array("givenname"=>$entries[$i]['givenname'][0], "sn"=>$entries[$i]['sn'][0]);
				if (isset($entries[$i]['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $entries[$i]['telephonenumber'][0]; } else { $people[$uid]['telephonenumber'] = ""; }
				if (isset($entries[$i]['mail'][0])) { $people[$uid]['mail'] = $entries[$i]['mail'][0]; } else { $people[$uid]['mail'] = ""; }
				if (isset($entries[$i]['displayname'][0]) && $entries[$i]['displayname'][0]) { $people[$uid]['displayname'] = $entries[$i]['displayname'][0]; } else { $people[$uid]['displayname'] = "{$entries[$i]['givenname'][0]} {$entries[$i]['sn'][0]}"; }
			}
			ksort($people);

			foreach ($people as $uid => $person)
			{
				# Choose the name to display
				if (isset($person['displayname']) and ($person['displayname'][0])) { $displayName = $person['displayname'][0]; }
				else { $displayName = "{$person['givenname'][0]} {$person['sn'][0]}"; }

				echo "
				<tr><td><a href=\"viewPerson.php?uid=$uid\">$person[displayname]</a></td>
					<td>$person[telephonenumber]</td>
					<td><a href=\"mailto:$person[mail]\">$person[mail]</td>
				</tr>
				";
			}
			echo "</table></li>";
		}
		echo "</ul></li>";
	}
	echo "</ul>";
?>
</div>
<?php
	include("$APPLICATION_HOME/includes/footer.inc");
	include("$GLOBAL_INCLUDES/xhtmlFooter.inc");
?>
