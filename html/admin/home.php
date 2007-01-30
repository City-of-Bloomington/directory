<?php
	$template = new Template();
	$template->blocks[] = new Block('loginForm.inc');
	$template->render();
?>