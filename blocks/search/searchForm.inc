<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
?>
<div class="titleBar">Search</div>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Search by Name</legend>
	<p>Enter the name of the person you wish to locate.  You can enter a partial name.</p>
	<table>
	<tr><td><label for="lastname">Last Name:</label></td>
		<td><input name="lastname" id="lastname" /></td></tr>
	<tr><td><label for="firstname">First Name:</label></td>
		<td><input name="firstname" id="firstname" /></td></tr>
	</table>
	<button type="submit" class="search">Search</button>
</fieldset>
<fieldset><legend>Search by Phone Number</legend>
	<p>You may also perform a reverse lookup on a four-digit extension</p>
	<table>
	<tr><td><label for="extension">Extension:</label></td>
		<td><input name="extension" id="extension" /></td></tr>
	</table>
	<button type="submit" class="search">Search</button>
</fieldset>
</form>