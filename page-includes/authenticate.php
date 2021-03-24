<?php
	session_start();
	if(session_status()==PHP_SESSION_NONE)
	{
		header('location:login.php?error="Please login or register an account"');
		exit('Not logged in.');
	}
	?>