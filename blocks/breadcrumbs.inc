<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->category
	( $this->department )
	( $this->location )
*/
	echo "
	<div class=\"breadcrumbs\">
		<a href=\"".BASE_URL."\">Departments</a> &gt;
		<a href=\"viewCategory.php?category={$this->category}\">{$this->category}</a> &gt;
	";
	if (isset($this->department)) { echo "<a href=\"viewDepartment.php?category={$this->category};department={$this->department}\">{$this->department}</a> &gt;"; }
	if (isset($this->location)) { echo "<a href=\"viewLocation.php?category={$this->category};department={$this->department};location={$this->location}\">{$this->location}</a>"; }
	echo "
	</div>
	";
?>