<div id="panel-one" class="panel">
<?php
	global $USER_ROLE;
	if ($USER_ROLE == 'Administrator') {
		$this->_include('partials/panel-widgets/Admin.inc');
	}
?>
</div>
