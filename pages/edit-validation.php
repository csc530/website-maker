<?php
	require_once 'authenticate.php';
	$error = 'Please try again.';
	//edit button to edit additional page content
	$editDetails = $_POST['edit'];
	//update button if they updated the title or description
	$update = $_POST['update'];
	//add button to add a new admin user
	$add = $_POST['add'];
	//website's name
	$siteName = $_GET['siteTitle'];
	//user to delete
	$delete = $_GET['delete'];
	//creator of website
	$creator = $_GET['creator'];
	
	//If they clicked the edit button redirect to edit-webpages to edit site's pages content
	if(!empty($editDetails))
	{
		header("location:edit-webpages.php?siteTitle=$siteName&creator=$creator&pageNumber=1");
		exit();
	}
	//if they clicked delete for one of the admin, delete that admin
	else if(!empty($delete))
	{
		try
		{
			$error = 'Network error please try again.';
			require 'connect.php';
			//no need for extensive checks as if the user is not there no delete is executed
			$sql = 'DELETE FROM websites_admin WHERE admin = :user AND creator = :creator AND siteName = :siteName';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':user', $delete, PDO::PARAM_STR, 128);
			$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->execute();
			$db = null;
			header("location:edit.php?siteTitle=$siteName&creator=$creator&msg=$delete removed as an admin to $siteName.");
			exit();
		}
		catch(Exception $exception)
		{
			header("location:edit.php?error=$error&siteTitle=$siteName&creator=$creator");
			exit();
		}
	}
	//If they entered a new admin to the site
	else if(!empty($add))
	{
		//new user email account
		$newUser = $_POST['user'];
		//basic email validation of admin email
		if(empty($newUser))
			$error = 'New admin user cannot be left blank.' . $newUser;
		else
		{
			try
			{
				$error = 'Network error please try again.';
				require 'connect.php';
				//query db for existing user with given email addr
				$sql = 'SELECT email FROM creators WHERE email = :user';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':user', $newUser, PDO::PARAM_STR, 128);
				$cmd->execute();
				$user = $cmd->fetch();
				$db = null;
				//if there is a registered user with given email add them as admin to website
				if($user['email'] == $newUser)
				{
					require 'connect.php';
					$sql = 'INSERT INTO websites_admin VALUES (:siteName, :creator, :admin);';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
					//Also db-side checking as user is a foreign key so that user must exist
					$cmd->bindParam(':admin', $newUser, PDO::PARAM_STR, 128);
					$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
					$cmd->execute();
					$db = null;
					header("location:edit.php?creator=$creator&siteTitle=$siteName");
					exit();
				}
				else
				{
					$error = "There is no registered user with $newUser email address. <a href='../signup.php?email=$newUser'>register</a> with it now.";
					header("location:edit.php?error=$error&creator=$creator&siteTitle=$siteName");
					exit();
				}
			}
			catch(Exception $exception)
			{
				header("location:edit.php?error=$error&creator=$creator&siteTitle=$siteName");
				exit();
			}
		}
	}
	else if(!empty($update))
	{
		//validation of new site's name and description
		$title = $_POST['title'];
		$description = $_POST['description'];
		if(empty($title))
			$error = 'Website title cannot be left blank.';
		else if(empty($description))
			$error = 'Website description cannot be left blank.';
		else if(strlen($description) > 600)
			$error = 'Description must be less than 600 characters.';
		else if(strlen($title) > 35)
			$error = 'Website title must be less than 35 characters';
		else if(strlen($title)<3)
			$error = 'Website name/title must at least be 3 characters';
		else if(strlen($description) < 2)
			$error = 'Website overview must at least be 2 characters';
		else
		{
			try
			{
				$error = 'Network error please try again.';
				require 'connect.php';
				//DB check because name is PK can't have duplicate named site
				$sql = 'UPDATE websites SET name = :name, description = :description WHERE name = :newName AND creatorID = :creator;';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':newName', $siteName, PDO::PARAM_STR, 35);
				$cmd->bindParam(':name', $title, PDO::PARAM_STR, 35);
				$cmd->bindParam(':description', $description, PDO::PARAM_STR, 600);
				$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
				//db-side checking as well that description and title is not nul
				$cmd->execute();
				$db = null;
				header("location:edit.php?creator=$creator&siteTitle=$title");
				exit();
			}
			catch(Exception $exception)
			{
				//check if the error is because they are using an already taken name
				require 'connect.php';
				$sql = 'SELECT * FROM websites WHERE name = :name AND creatorID = :creator;';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':name', $siteName, PDO::PARAM_STR, 35);
				$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
				$cmd->execute();
				$exits = $cmd->fetch();
				if(!empty($exits))
					$error = "You already have a website with the name $siteName";
				header("location:edit.php?error=$error&creator=$creator&siteTitle=$siteName");
				exit();
			}
		}
	}
	header("location:edit.php?error=$error&creator=$creator&siteTitle=$siteName");
	exit();
?>