<?php
/*
	Logs a user into the system.
	A logged in user will have a $_SESSION['USER_ID']
								$_SESSION['IP_ADDRESS']


	$_POST Variables:	username
						password
*/
	$user_id = authenticate($_POST['username'],$_POST['password']);
	if ($user_id) { create_session($user_id); }


	Header("Location: $BASE_URL");
?>
