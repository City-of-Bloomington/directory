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
		if ($firstname || $lastname) { $query = "(|(givenName=$firstname*)(displayName=$firstname*))(|(sn=$lastname*)(sn=*-$lastname*))"; }
		elseif ($extension) { $query = "(telephoneNumber=*$extension)"; }


		# Do the search
	 	$results=$adldap->user()->find(false, $query);
		$people = array();

		$count = sizeof($results);
		# If we only get one hit back, send them directly to that person
		if ($count == 1)
		{
			# Check to see if user is disabled
			$user = $adldap->user()->infoCollection($results[0], array('cn'));
			if (preg_match('/^[^\*]/', $user->cn)) { 
				Header("Location: viewPerson.php?uid={$results[0]}");
				exit();
			}
			
		}
		else
		{
			# Otherwise, show all the people we found
			for($i = 0; $i < $count; $i++) 	
			{
				$uid = $results[$i];
				$user = $adldap->user()->infoCollection($uid, array('givenname', 'telephonenumber', 
					'mail', 'displayname', 'sn', 'cn', 'title'));	
				if (preg_match('/^\*/', $user->cn)) { continue; }
				if ($uid)
				{
					$people[$uid] = array("givenname"=>$user->givenname, "sn"=>$user->sn);
					if ($user->telephonenumber) { 
						$people[$uid]['telephonenumber'] = $user->telephonenumber; 
					} else { $people[$uid]['telephonenumber'] = ""; }

					if ($user->mail) { 
						$people[$uid]['mail'] = $user->mail; 
					} else { $people[$uid]['mail'] = ""; }

					if ($user->displayname) { 
						$people[$uid]['displayname'] = $user->displayname; 
					} else { $people[$uid]['displayname'] = "{$user->givenname} {$user->sn}"; }

					if ($user->title) { 
						$people[$uid]['title'] = $user->title; 
					} else { $people[$uid]['title'] = ""; }
				}
			}
			ksort($people);
		}
		$template->blocks[] = new Block('search/searchResults.inc',array('people'=>$people));
	}

	$template->blocks[] = new Block('search/searchForm.inc');
	$template->render();
?>
