<?php
	$error = 'Please try again.';
	//edit button to edit additional page content
	$editPage = $_POST['edit'];
	//update button if they updated the title or description
	$basic = $_POST['update'];
	//add button to add a new admin user
	$addUser = $_POST['add'];
	//new user email account
	$newUser = $_POST['user'];
	//website's ID
	$websiteID = $_POST['websiteID'];
	if($editPage == 'true')
		header("location:edit-page.php?pageID=$websiteID");
	else if(!empty($addUser))
	{
		try
		{
			$error='Network error please try again.';
			require_once '../connect.php';
			$sql = 'SELECT email FROM users WHERE email = :user';
			$cmd = $db->prepare($sql);
			$cmd->execute();
			$user = $cmd->fetch();
			if($user['email'] == $newUser)
			{
				$sql = 'INSERT INTO websites_admin VALUES (:websiteID, :user);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':websiteID', $websiteID, PDO::PARAM_INT, 11);
				//db side checking as user is a foreign key so that user must exist
				$cmd->bindParam(':user', $newUser, PDO::PARAM_STR, 128);
				$cmd->execute();
				//todo ui: pass get variable to allow the new user to be highlighted for a time or something
				header("location:edit.php");
			}
			else
			{
				$error = "There is no registered user with $newUser email address. <a href='../signup.php?email=$newUser'>register</a> with it now.";
				header("location:edit.php?error=$error");
			}
		}
		catch(Exception $exception)
		{
			header("location:edit.php?error=$error");
		}
	}
	else if(!empty())
		?>