<?php
	$error = 'Please try again.';
	//edit button to edit additional page content
	$editDetails = $_POST['edit'];
	//update button if they updated the title or description
	$update = $_POST['update'];
	//add button to add a new admin user
	$add = $_POST['add'];
	//website's ID
	$websiteID = $_GET['websiteID'];
	//user to delete
	$delete = $_GET['delete'];
	if(!empty($editDetails))
		header("location:edit-page.php?pageID=$websiteID");
	else if(!empty($delete)){
		try
		{
			$error = 'Network error please try again.';
			require_once '../connect.php';
			//no need for extensive checks as if the user is not there no delete is executed
			$sql = 'DELETE FROM websites_admin WHERE admin = :user';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':user', $delete, PDO::PARAM_STR, 128);
			$cmd->execute();
		}
		catch(Exception $exception)
		{
			header("location:edit.php?error=$error");
		}
	}
	else if(!empty($add))
	{
		//new user email account
		$newUser = $_POST['user'];
		if(empty($user))
			$error = 'New admin user cannot be left blank.';
		else
		{
			try
			{
				$error = 'Network error please try again.';
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
					//Also db-side checking as user is a foreign key so that user must exist
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
	}
	else if(!empty($update))
	{
		$title = $_POST['title'];
		$description = $_POST['description'];
		if(empty($title))
			$error = 'Website title cannot be left blank.';
		else if(empty($description))
			$error = 'Website description cannot be left blank.';
		else if(strlen($description)>600)
			$error = 'Description must be less than 600 characters.';
		else if(strlen($title))
			$error = 'Website title must be less than 35 characters';
		else
		{
			try
			{
				$error = 'Network error please try again.';
				require_once '../connect.php';
					$sql = 'UPDATE websites SET name = :name AND description = :description WHERE ID = :ID';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':websiteID', $websiteID, PDO::PARAM_INT, 11);
					$cmd->bindParam(':name', $title,PDO::PARAM_STR, 35);
					$cmd->bindParam(':description', $description, PDO::PARAM_STR, 600);
					//db-side checking as well that description and title is not nul
					$cmd->execute();
					//todo ui: pass get variable to allow the new user to be highlighted for a time or something
					header("location:edit.php");
							}
			catch(Exception $exception)
			{
				header("location:edit.php?error=$error");
			}
		}
	}
		?>