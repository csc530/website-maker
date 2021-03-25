<?php
	$redirect = true;
	require_once 'authenticate.php';
	$step = $_POST['step'];
	$error = 'Please try again';
	//check what step of the website building process user is on
	if($step==1)
	{
		$title = trim($_POST['title']);
		$description = trim($_POST['description']);
		if(empty($title))
			$error = 'Website title cannot be empty';
		else if(empty($description))
			$error = 'Website description cannot be empty';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				$sql = 'INSERT INTO websites (creator,name, description) VALUES (:email,:title,:desc);';
				$cmd = $db->prepare($sql);
				$email = $_SESSION['email'];
				$cmd->bindParam(':email' , $email, PDO::PARAM_STR, 128);
				$cmd->bindParam(':title', $title, PDO::PARAM_STR, 35);
				$cmd->bindParam(':desc', $description, PDO::PARAM_STR, 600);
				$cmd->execute();
				header("location:edit-webpages.php?title=$title&pageNumber=1");
			}
			catch(Exception $exception)
			{
				header("location:create.php?error=$error");
			}
		}
	}
	else if($step==2)
	{
		$pageNumber = $_GET['pageNumber'];
		$pageTitle = trim($_POST['pageTitle']);
		$title = trim($_GET['title']);
		$content = trim($_POST['pageContent']);
		$webID = $_GET['webID'];
		if(empty($title))
			$error = 'Page title cannot be empty';
		else if(empty($description))
			$error = 'Page content cannot be empty';
		else
		{		echo "bepp";
			
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				$sql = 'INSERT INTO pages VALUES (:title,:content,:webID,:pgNum);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':title' , $title, PDO::PARAM_STR, 50);
				$cmd->bindParam(':content', $content, PDO::PARAM_STR, 10000);
				$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':pgNum',$pageNumber, PDO::PARAM_INT, 11);
				$cmd->execute();
				//increment the page number as to 'add' a new page
				$pageNumber++;
				header("location:edit-webpages.php?title=$title&pageNumber=$pageNumber&webID=$webID");
			}
			catch(Exception $exception)
			{
				header("location:create.php?error=$error");
			}
		}
	}
	?>