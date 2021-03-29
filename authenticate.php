<?php
	//return if user is logged in in 'loggedIn' variable and if redirect is true will redirect them to the login page
	//The reason it doesn't always redirect is for choosing the header so it won't infinitely loop when they are not logged in
	session_start();
	$loggedIn = !empty($_SESSION['email']);
	if($redirect===false && !$loggedIn)
	{
		header('location:login.php?error="Please login or register an account"');
		exit();
	}
	?>