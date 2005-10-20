<?php
/*
	Lists all the people in a department

	$_GEt variables:	department
*/
	include("$GLOBAL_INCLUDES/xhtmlHeader.inc");
	include("$APPLICATION_HOME/includes/banner.inc");
	include("$APPLICATION_HOME/includes/menubar.inc");
	include("$APPLICATION_HOME/includes/navigation.inc");
?>
<div id="mainContent">
	<h1><?php echo $_GET['department']; ?></h1>
	<table>

	<?php
		# Do the search
		$results = ldap_search($LDAP_SERVER, $LDAP_DN, "departmentNumber=$_GET[department]");
		$entries = ldap_get_entries($LDAP_SERVER, $results);

		for ($i = 0; $i < $entries['count']; $i++)
		{
			$uid = $entries[$i]['uid'][0];
			$people[$uid] = array("givenname"=>$entries[$i]['givenname'][0], "sn"=>$entries[$i]['sn'][0]);
			if (isset($entries[$i]['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $entries[$i]['telephonenumber'][0]; } else { $people[$uid]['telephonenumber'] = ""; }
			if (isset($entries[$i]['mail'][0])) { $people[$uid]['mail'] = $entries[$i]['mail'][0]; } else { $people[$uid]['mail'] = ""; }
		}
		ksort($people);

		foreach ($people as $uid => $person)
		{
			echo "
			<tr><td><a href=\"viewPerson.php?uid=$uid\">$person[givenname] $person[sn]</a></td>
				<td>$person[telephonenumber]</td>
				<td><a href=\"mailto:$person[mail]\">$person[mail]</td>
			</tr>
			";
		}
	?>

	</table>
</div>
<?php
	include("$APPLICATION_HOME/includes/footer.inc");
	include("$GLOBAL_INCLUDES/xhtmlFooter.inc");
?>
