<?php
/*
	We want all city applications to have the same standard footer.  Please use this footer.

	Variables:	$startTime should have been set in the xhtmlHeader.inc
*/
	# Calculate the process time
	$endTime = microtime(1);
	$processTime = $endTime - $startTime;
?>
<p>Email <a href="mailto:helpdesk@bloomington.in.gov">helpdesk@bloomington.in.gov</a>
	or phone 349-3454 for assistance.
	Information &amp; Technology Services Department, City of Bloomington, IN
	<!-- Process Time: <?php echo $processTime; ?> -->
</p>

