<?php
	// access current session
	echo session_start();
	// remove all session variables
	echo session_unset();
	// terminate the session
	echo session_destroy();
	// redirect to main page
	header('location:index.php');
	exit();
?>