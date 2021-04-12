<?php
	$error = "Please try again.";
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$cPassword = trim($_POST['confirm-password']);
	$login = $_POST['login'];
	//if they are logging in
	if(!empty($login))
	{
		try
		{
			$error = 'Network error, please try again.';
			require_once 'connect.php';
			//query db for password belonging to given email addr
			$sql = 'SELECT password FROM creators WHERE email = :email';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$success = $cmd->fetch();
			//maybe not the safest to indicate whether password or email is wrong but personally I like it as a user to know what to adjust
			//if no results are returned in $success variable email address is wrong
			if(empty($success))
				$error = "No user with email, $email, was found please <a href='register.php?email=$email'>register</a> now.";
			else
			{
				//check if entered password matches hashed password in the db
				if(password_verify($password, $success['password']))
				{
					$sql = 'SELECT ID FROM creators WHERE email = :email';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
					$db = null;
					$cmd->execute();
					session_start();
					$_SESSION['id'] = $cmd->fetch()['ID'];
					$_SESSION['email'] = $email;
					header("location:menu.php");
					exit();
				}
				else
					$error = 'Incorrect password';
			}
			header("location:login.php?error=$error");
			exit();
		}
		catch(Exception $exception)
		{
			$db = null;
			$return = "login.php?$email";
			header("location:err.php?return=$email&error=$error");
			exit();
		}
	}
	else
	{
		//basic validation of email and password
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
				//insert new user to db
				$sql = 'INSERT INTO creators(email, password) VALUES (:username, :password);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':username', $email, PDO::PARAM_STR, 128);
				$password = password_hash($password, PASSWORD_DEFAULT);
				$cmd->bindParam(':password', $password, PDO::PARAM_STR, 128);
				//Registered email is PK so if the query fails it will 99% of the time be because they are
				//registering an already bound email address
				$cmd->execute();
				//get newly created creatorId to store in session
				$sql = 'SELECT ID FROM creators WHERE email = :email';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
				$db = null;
				$cmd->execute();
				//start a session of newly created user
				session_start();
				$_SESSION['email'] = $email;
				//add creator id to session
				$_SESSION['id'] = $cmd->fetch()['ID'];
				header("location:menu.php");
				exit();
			}
			catch(Exception $exception)
			{
				//check if email is already bound and update errorMsg accordingly
				require 'connect.php';
				$sql = 'SELECT email FROM creators WHERE email = :email';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
				$cmd->execute();
				$success = $cmd->fetch();
				$db = null;
				if(!empty($success))
					$error = "$email is already bound to an account please <a href='pages/login.php'>login</a>.";
				else
				{
					header("location:err.php?return=signup.php");
					exit();
				}
			}
		}
		if(empty($_POST['superuser']))
			header("location:signup.php?error=$error");
		else
			header("location:super-user.php?error=$error");
		exit();
	}
?>