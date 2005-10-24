<div id="footer">
	<div class="menuBar">
		<a href="http://bandit.bloomington.in.gov/svn/listing.php?repname=Directory;path=%2F;sc=0">Subversion</a>
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