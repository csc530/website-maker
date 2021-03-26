<?php
	$redirect = true;
	require_once 'authenticate.php';
	$step = $_POST['step'];
	$error = 'Please try again';
	//check what step of the website building process user is on
	if($step==1)
	{
		$siteTitle = trim($_POST['title']);
		$description = trim($_POST['description']);
		if(empty($siteTitle))
			$error = 'Website title cannot be empty';
		else if(empty($description))
			$error = 'Website description cannot be empty';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				//todo: validate that they can't have two website of the same name
				$sql = 'INSERT INTO websites (creator,name, description) VALUES (:email,:title,:desc);';
				$cmd = $db->prepare($sql);
				$email = $_SESSION['email'];
				$cmd->bindParam(':email' , $email, PDO::PARAM_STR, 128);
				$cmd->bindParam(':title', $siteTitle, PDO::PARAM_STR, 35);
				$cmd->bindParam(':desc', $description, PDO::PARAM_STR, 600);
				$cmd->execute();
				header("location:edit-webpages.php?siteTitle=$siteTitle&pageNumber=1");
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
		$siteTitle = $_GET['siteTitle'];
		$content = trim($_POST['pageContent']);
		$webID = $_GET['webID'];
		if(empty($pageTitle))
			$error = 'Page title cannot be empty';
		else if(empty($content))
			$error = 'Page content cannot be empty';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				$sql = 'INSERT INTO pages VALUES (:title,:content,:webID,:pgNum);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':title' , $pageTitle, PDO::PARAM_STR, 50);
				$cmd->bindParam(':content', $content, PDO::PARAM_STR, 10000);
				$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':pgNum',$pageNumber, PDO::PARAM_INT, 11);
				$cmd->execute();
				//increment the page number as to 'add' a new page
				$pageNumber= $pageNumber+1;
				header("location:edit-webpages.php?siteTitle=$siteTitle&pageNumber=$pageNumber&webID=$webID");
				exit();
			}
			catch(Exception $exception)
			{
				//i'm going to say 99% of the time it will throw an error is because they already have a page with that name and since it's PK the db will throw an exception
				//todo: check if they already have a page with that name
				$error = 'You already have a webpage with that name';
				header("location:edit-webpages.php?siteTitle=$siteTitle&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
				exit();
			}
		}
		header("location:edit-webpages.php?siteTitle=$siteTitle&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
		exit();
	}
	else
	{
		$webID = $_GET['webID'];
		header("location:publish.php?webID=$webID");
		exit();
	}
	?>