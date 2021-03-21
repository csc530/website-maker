<?php
	$error = "Please try again.";
	$email = $_POST['username'];
	$password = $_POST['password'];
	$cPassword = $_POST['confirm-password'];
	$login = $_POST['login'];
	if($login)
	{
		$error = "No user with email, $email, was found please <a href='register.php?email=$email'>register</a> now.";
		require_once 'connect.php';
		$sql = 'SELECT password FROM users WHERE email = :email';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
		$cmd->execute();
		$success = $cmd->fetch();
		//check if entered password matches hashed password in the db
		if(password_verify($password, $success['password']))
			header("location:menu.php");
		else
			$error = 'Incorrect password';
		header("location:login.php?error=$error");
	}
	else
	{
		if(empty($email))
			$error = 'username cannot be empty.';
		else if(empty($password))
			$error = 'password cannot be empty.';
		else if(empty($cPassword) || $cPassword != $password)
			$error = 'passwords do not match.';
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
				header("location:menu.php");
			}
			catch(Exception $exception)
			{
				$error = "$email is already bound to an account please <a href='login.php'>login</a>.";
			}
		}
		header("location:signup.php?error=$error");
	}
?>