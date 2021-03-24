<?php
	$error = "Please try again.";
	$email = $_POST['email'];
	$password = $_POST['password'];
	$cPassword = $_POST['confirm-password'];
	$login = $_POST['login'];
	if(!empty($login))
	{
		$error = "No user with email, $email, was found please <a href='register.php?email=$email'>register</a> now.";
		try
		{
			require_once 'connect.php';
			$sql = 'SELECT password FROM creators WHERE email = :email';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$success = $cmd->fetch();
			//check if entered password matches hashed password in the db
			if(password_verify($password, $success['password']))
			{
				session_start();
				header("location:menu.php");
				exit();
			}
			else
				$error = 'Incorrect password';
			header("location:login.php?error=$error");
			exit();
		}
		catch(Exception $exception)
		{		header("location:login.php?error=$error");
		exit();
		}
	}
	else
	{
		if(empty($email))
			$error = 'email cannot be empty.';
		else if(empty($password))
			$error = 'password cannot be empty.';
		else if(empty($cPassword) || $cPassword != $password)
			$error = 'passwords do not match.';
		else
		{
			try
			{
				require_once 'connect.php';
				$sql = 'INSERT INTO creators VALUES (:username, :password);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':username', $email, PDO::PARAM_STR, 128);
				$password = password_hash($password, PASSWORD_DEFAULT);
				$cmd->bindParam(':password', $password, PDO::PARAM_STR, 128);
				//Registered email as PK so if the query fails it will 99% of the time be because they are
				//registering an already bound email address
				 	$success = $cmd->execute();
				$db = null;
				header("location:menu.php");
				//insurance/verification that no other code is executed
				exit();
			}
			catch(Exception $exception)
			{
				$error = "$email is already bound to an account please <a href='pages/login.php'>login</a>.";
			}
		}
		header("location:signup.php?error=$error");
	exit();}
?>