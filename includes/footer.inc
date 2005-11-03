<div class="menuBar">
	<a href="http://bandit.bloomington.in.gov/svn/listing.php?repname=Directory;path=%2F;sc=0">Subversion</a>
	<?php
		# Show if they're logged in or not
		if (isset($_SESSION['USERNAME'])) { echo "Logged in as $_SESSION[USERNAME]"; }
	?>
</div>
<div id="footer">
	<?php include("$GLOBAL_INCLUDES/footer.inc"); ?>
</div>
