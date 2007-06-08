<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param string $this->category
 * @param array $this->departments
 */
	$jsonDepartments = array();
	foreach($this->departments as $department=>$offices)
	{
		$jsonOffices = array();
		foreach($offices as $office=>$people)
		{
			$jsonPeople = array();
			foreach ($people as $uid => $person)
			{
				$name = View::escape($person['displayname']);
				$title = View::escape($person['title']);
				$phone = View::escape($person['telephonenumber']);
				$email = View::escape($person['mail']);
				$jsonPeople[] = "{'name':'$name','title':'$title','phone':'$phone','email':'$email'}";
			}
			$jsonPeople = implode(",\n\t\t\t",$jsonPeople);

			$name = View::escape($office);
			$jsonOffices[] = "{'name':'$name','people':[$jsonPeople]}";
		}
		$jsonOffices = implode(",\n\t\t",$jsonOffices);

		$name = View::escape($department);
		$jsonDepartments[] = "{'name':'$name','offices':[$jsonOffices]}";


	}
	$jsonDepartments = implode(",\n\t",$jsonDepartments);

echo "
[
	$jsonDepartments
]
";
?>