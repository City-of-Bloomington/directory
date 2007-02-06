<?php
/*
	Performs a search. If multiple hits are returned, displays a list of names. If a single hit is returned,
	redirects to that person's info page.

	$_GET variables:	firstName - Full or partial first name of person to locate.
						lastName - Full or partial last name of person to locate.
						extension - Four-digit extension of person to locate. Ignored if
									firstName or lastName are specified.
*/
	$template = new Template();

	# Clean all the stuff they typed
	$lastname = isset($_GET['lastname']) ? trim($_GET['lastname']) : "";
	$firstname = isset($_GET['firstname']) ? trim($_GET['firstname']) : "";
	$extension = isset($_GET['extension']) ? trim($_GET['extension']) : "";


	if ($lastname || $firstname || $extension)
	{
		# begin easter egg
		if ($lastname == "monkeys")
		{
			Header("Location: monkeys.php");
			exit();
		}
		# end easter egg


		# Build the LDAP query
		if ($firstname || $lastname) { $query = "(&(|(givenName=$firstname*)(displayName=$firstname*))(|(sn=$lastname*)(sn=*-$lastname*)))"; }
		elseif ($extension) { $query = "telephoneNumber=*$extension"; }


		# Do the search
		$results = ldap_search($LDAP_CONNECTION, LDAP_DN, $query);
		$entries = ldap_get_entries($LDAP_CONNECTION, $results);
		$people = array();

		# If we only get one hit back, send them directly to that person
		if ($entries['count'] == 1)
		{
			Header("Location: viewPerson.php?uid={$entries[0]['uid'][0]}");
			exit();
		}
		else
		{
			# Otherwise, show all the people we found
			foreach($entries as $entry)
			{
				$uid = $entry['uid'][0];
				if ($uid)
				{
					$people[$uid] = array("givenname"=>$entry['givenname'][0], "sn"=>$entry['sn'][0]);
					if (isset($entry['telephonenumber'][0])) { $people[$uid]['telephonenumber'] = $entry['telephonenumber'][0]; } else { $people[$uid]['telephonenumber'] = ""; }
					if (isset($entry['mail'][0])) { $people[$uid]['mail'] = $entry['mail'][0]; } else { $people[$uid]['mail'] = ""; }
					if (isset($entry['displayname'][0]) && $entry['displayname'][0]) { $people[$uid]['displayname'] = $entry['displayname'][0]; } else { $people[$uid]['displayname'] = "{$entry['givenname'][0]} {$entry['sn'][0]}"; }
					if (isset($entry['title'][0]) && $entry['title'][0]) { $people[$uid]['title'] = $entry['title'][0]; } else { $people[$uid]['title'] = "{$entry['givenname'][0]} {$entry['sn'][0]}"; }
				}
			}
			ksort($people);
		}
		$template->blocks[] = new Block('search/searchResults.inc',array('people'=>$people));
	}

	$template->blocks[] = new Block('search/searchForm.inc');
	$template->render();
?>