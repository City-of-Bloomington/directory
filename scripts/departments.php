<html>
<body>
<ul>
<?php
	# Get all the businessCategories
	$result = ldap_search($LDAP_SERVER, $LDAP_DN, "objectclass=*",array("businessCategory"));
	$entries = ldap_get_entries($LDAP_SERVER, $result);

	$categories = array();
	for ($i=0; $i<$entries['count']; $i++)
	{
		if (isset($entries[$i]['businesscategory'][0]) && !in_array($entries[$i]['businesscategory'][0],$categories)) { $categories[] = $entries[$i]['businesscategory'][0]; }
	}
	asort($categories);
	foreach($categories as $category)
	{
		echo "\t<li>$category\n\t\t<ul>\n";

		# Get all the departments in this category
		$result = ldap_search($LDAP_SERVER,$LDAP_DN,"businessCategory=$category",array("departmentNumber"));
		$entries = ldap_get_entries($LDAP_SERVER,$result);

		$departments = array();
		for($i=0; $i<$entries['count']; $i++)
		{
			if(isset($entries[$i]['departmentnumber'][0]) && !in_array($entries[$i]['departmentnumber'][0],$departments)) { $departments[] = $entries[$i]['departmentnumber'][0]; }
		}
		asort($departments);
		foreach($departments as $department)
		{
			echo "\t\t\t<li>$department\n\t\t\t\t<ul>\n";

			# Get all the deliveryOffices
			$result = ldap_search($LDAP_SERVER,$LDAP_DN,"(&(businessCategory=$category)(departmentNumber=$department))",array("physicalDeliveryOfficeName"));
			$entries = ldap_get_entries($LDAP_SERVER,$result);

			$offices = array();
			for($i=0; $i<$entries['count']; $i++)
			{
				if(isset($entries[$i]['physicaldeliveryofficename'][0]) && !in_array($entries[$i]['physicaldeliveryofficename'][0],$offices)) { $offices[] = $entries[$i]['physicaldeliveryofficename'][0]; }
			}
			asort($offices);
			foreach($offices as $office)
			{
				echo "\t\t\t\t\t<li>$office</li>\n";
			}

			echo "\t\t\t\t</ul>\n\t\t\t</li>\n";
		}
		echo "\t\t</ul>\n\t</li>\n";
	}
?>
</ul>
</body>
</html>