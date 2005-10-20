<?php
	include("$GLOBAL_INCLUDES/xhtmlHeader.inc");
	include("$APPLICATION_HOME/includes/banner.inc");
	include("$APPLICATION_HOME/includes/menubar.inc");
	include("$APPLICATION_HOME/includes/navigation.inc");
?>
<div id="mainContent">
	<?php include("$GLOBAL_INCLUDES/errorMessages.inc"); ?>

	<h1>Search</h1>

	<form method="get" action="search.php">
	<fieldset><legend>Search by name</legend>
		<table>
		<tr><td><label for="lastname">Last Name:</label></td>
			<td><input name="lastname" id="lastname" /></td></tr>
		<tr><td><label for="firstname">First Name:</label></td>
			<td><input name="firstname" id="firstname" /></td></tr>
		</table>
	</fieldset>

	<fieldset><legend>Search by number</legend>
		<table>
		<tr><td><label for="extension">Extension:</label></td>
			<td><input name="extension" size="5" /></td></tr>
		</table>

		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>

</div>
<?php
	include("$APPLICATION_HOME/includes/footer.inc");
	include("$GLOBAL_INCLUDES/xhtmlFooter.inc");
?>
