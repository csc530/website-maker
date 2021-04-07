<?php
	// access current session
	session_start();
	// remove all session variables
	session_unset();
	// terminate the session
	session_destroy();
	// redirect to main page
	header('location:index.php?msg=Successfully logged out');
	exit();
?>