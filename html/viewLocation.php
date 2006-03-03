<?php
/*
	Lists all the people in a department

	$_GET variables:	department
						category
						location
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
	<?php
		echo "
		<div class=\"breadcrumbs\">
			<a href=\"".BASE_URL."\">Departments</a> &gt;
			<a href=\"viewCategory.php?category=$_GET[category]\">$_GET[category]</a> &gt;
			<a href=\"viewDepartment.php?category=$_GET[category];department=$_GET[department]\">$_GET[department]</a> &gt;
			<a href=\"viewLocation.php?category=$_GET[category];department=$_GET[department];location=$_GET[location]\">$_GET[location]</a>
		</div>

		<table>
		";

		# Do the search
		$results = ldap_search($LDAP_CONNECTION,LDAP_DN,"(&(businessCategory=$_GET[category])(departmentNumber=$_GET[department])(physicalDeliveryOfficeName=$_GET[location]))");
		$entries = ldap_get_entries($LDAP_CONNECTION, $results);

		for ($i = 0; $i < $entries['count']; $i++)
		{
			$uid = $entries[$i]['uid'][0];
			$people[$uid] = array("givenname"=>$entries[$i]['givenname'][0], "sn"=>$entries[$i]['sn'][0]);
			if (isset($entries[$i]['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $entries[$i]['telephonenumber'][0]; } else { $people[$uid]['telephonenumber'] = ""; }
			if (isset($entries[$i]['mail'][0])) { $people[$uid]['mail'] = $entries[$i]['mail'][0]; } else { $people[$uid]['mail'] = ""; }
			if (isset($entries[$i]['displayname'][0]) && $entries[$i]['displayname'][0]) { $people[$uid]['displayname'] = $entries[$i]['displayname'][0]; } else { $people[$uid]['displayname'] = "{$entries[$i]['givenname'][0]} {$entries[$i]['sn'][0]}"; }
			if (isset($entries[$i]['title'][0]) && $entries[$i]['title'][0]) { $people[$uid]['title'] = $entries[$i]['title'][0]; } else { $people[$uid]['title'] = "{$entries[$i]['givenname'][0]} {$entries[$i]['sn'][0]}"; }
		}
		ksort($people);

		foreach ($people as $uid => $person)
		{
			echo "
			<tr><td><a href=\"viewPerson.php?uid=$uid\">$person[displayname]</a>, $person[title]</td>
				<td>$person[telephonenumber]</td>
				<td><a href=\"mailto:$person[mail]\">$person[mail]</td>
			</tr>
			";
		}
	?>

	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>

