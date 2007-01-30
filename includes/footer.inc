<div id="footer">
	<div class="menuBar">
		<a href="http://bandit.bloomington.in.gov/svn/listing.php?repname=directory;path=%2F;sc=0">Source Code</a>
		<?php
			# Show if they're logged in or not
			if (isset($_SESSION['USER'])) { echo "Logged in as ".$_SESSION['USER']->getUsername(); }
		?>
	</div>
	<?php include(FRAMEWORK."/footer.inc"); ?>
</div>
