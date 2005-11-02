<div id="footer">
	<div class="menuBar">
		<a href="http://bandit.bloomington.in.gov/svn/listing.php?repname=Directory;path=%2F;sc=0">Subversion</a>
		<?php
			# If they're nto logged in, draw the login form
			if (!isset($_SESSION['USERNAME']))
			{
				echo "
				<form method=\"post\" action=\"authenticate.php\">
				<fieldset>
					<label>user:<input name=\"username\" /></label>
					<label>pass:<input name=\"password\" type=\"password\" /></label>
					<button type=\"submit\" class=\"login\">Login</button>
				</fieldset>
				</form>
				";
			}
		?>
	</div>
	<p>Email corrections to <a href="mailto:helpdesk@bloomington.in.gov?subject=Directory%20correction">helpdesk@bloomington.in.gov</a>.</p>
<?php
	#---------------------------------------------------------------------
	# Before we quit, handle any Garbage Collection that needs to be done
	#---------------------------------------------------------------------

	# If we're on a form, clean out any old data
	if (isset($_SESSION['oldPostData'])) unset($_SESSION['oldPostData']);

	# For debugging purposes.
	$endTime = microtime(1);
	$processTime = $endTime - $startTime;
	echo "<p>ProcessTime: $processTime seconds</p>";
?>
</div>
