<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>
	<div class="titleBar">Admin</div>

	<div id="loginBox">
		<form id="loginBox" method="post" action="authenticate.php">
		<fieldset><legend>Login</legend>
			<input name="returnURL" type="hidden" value="<?php echo BASE_URL; ?>" />
			<table>
			<tr><td><label for="username">Username:</label></td>
				<td><input name="username" id="username" /></td></tr>
			<tr><td><label for="password">Password:</label></td>
				<td><input name="password" id="password" type="password" /></td></tr>
			</table>

			<button type="submit" class="login">Login</button>
		</fieldset>
		</form>

		<script type="text/javascript">document.forms[1].username.focus();</script>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
