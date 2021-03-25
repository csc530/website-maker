<?php
	session_start();
	$loggedIn = session_status()==PHP_SESSION_NONE;
	if(!empty($redirect) && !$loggedIn)
	{
		header('location:login.php?error="Please login or register an account"');
		exit();
	}
	?>