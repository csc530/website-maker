<?php
	$title = "Registering";
    $email = $_POST['username'];
    $password = $_POST['password'];
    $cPassword = $_POST['confirm-password'];
    if(empty($email))
    	$title = 'username cannot be empty.';
    else if(empty($password))
    	$title = 'password cannot be empty.';
    else if(empty($cPassword) || $cPassword!=$password)
    	$title='passwords do not match.';
    else
    {
    	try
	    {
		    require_once 'connect.php';
		    $sql = 'INSERT INTO users (:username, :password);';
		    $cmd = $db->prepare($sql);
		    $cmd->bindParam(':username', $email, PDO::PARAM_STR, 128);
		    $password = password_hash($password, PASSWORD_DEFAULT);
		    $cmd->bindParam(':password', $password, PDO::PARAM_STR, 128);
		    $success = $cmd->execute();
		    $db = null;
		    header("location:menu.php?error=$title");
	    }
	    catch(Exception $exception){
    		$title = "$email is already bound to an account please <a href='login.php'>login</a>.";
	    }
    }
	header("location:signup.php?error=$title");
?>