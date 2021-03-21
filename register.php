<?php
	$title = "Registering";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cPassword = $_POST['confirm-password'];
    if(empty($username))
    	$title = 'username cannot be empty.';
    else if(empty($password))
    	$title = 'password cannot be empty.';
    else if(empty($cPassword) || $cPassword!=$password)
    	$title='passwords do not match.';
    else
    {
	    require_once 'connect.php';
	    //upload new user to db
	    $db = null;
		header("location:menu.php?error=$title");
    }
	header("location:signup.php?error=$title");
?>