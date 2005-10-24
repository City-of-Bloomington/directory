<?php
/*
	Performs a search. If multiple hits are returned, displays a list of names. If a single hit is returned,
	redirects to that person's info page.

	$_GET variables:	firstName - Full or partial first name of person to locate.
						lastName - Full or partial last name of person to locate.
						extension - Four-digit extension of person to locate. Ignored if
									firstName or lastName are specified.
*/
	include("$GLOBAL_INCLUDES/xhtmlHeader.inc");
	include("$APPLICATION_HOME/includes/banner.inc");
	include("$APPLICATION_HOME/includes/menubar.inc");
?>
<div id="mainContent">
	<h1>Search Results</h1>
	<table>
	<?php
		# Clean all the stuff they typed
		$_GET['firstname'] = sanitize($_GET['firstname']);
		$_GET['lastname'] = sanitize($_GET['lastname']);
		$_GET['extension'] = sanitize($_GET['extension']);

		# begin easter egg
		if ($_GET['lastname'] == "monkeys")
		{
			Header("Location: monkeys.php");
			exit();
		}
		# end easter egg


		# Build the LDAP query
		if ($_GET['firstname'] || $_GET['lastname']) { $query = "(&(|(givenName=$_GET[firstname]*)(displayName=$_GET[firstname]*))(sn=$_GET[lastname]*))"; }
		elseif ($_GET['extension']) { $query = "telephoneNumber=*$_GET[extension]"; }
		else { $_SESSION['errorMessages'][] = "missingNameOrExtension"; }


		# Check for errors before moving on
		if (isset($_SESSION['errorMessages']))
		{
			Header("Location: home.php");
			exit();
		}


		# Do the search
		$results = ldap_search($LDAP_SERVER, $LDAP_DN, $query);
		$entries = ldap_get_entries($LDAP_SERVER, $results);

		# If we only get one hit back, send them directly to that person
		if ($entries['count'] == 1)
		{
			Header("Location: viewPerson.php?uid={$entries[0]['uid'][0]}");
			exit();
		}
		else
		{
			# Otherwise, show all the people we found
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
		}
	?>
	</table>
</div>
<?php
	include("$APPLICATION_HOME/includes/footer.inc");
	include("$GLOBAL_INCLUDES/xhtmlFooter.inc");
?>
