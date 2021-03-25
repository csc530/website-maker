<?php
	session_start();
	$loggedIn = !empty($_SESSION['email']);
	if(!empty($redirect) && !$loggedIn)
	{
		header('location:login.php?error="Please login or register an account"');
		exit();
	}
	?>