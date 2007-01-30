<?php
	include(FRAMEWORK."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
	<?php
		include(FRAMEWORK."/errorMessages.inc");

		$this->includeBlocks();
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(FRAMEWORK."/xhtmlFooter.inc");
?>