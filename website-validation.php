<?php
	$redirect = true;
	require_once 'authenticate.php';
	$step = $_POST['step'];
	$error = 'Please try again';
	//check what step of the website building process user is on
	if($step==1)
	{
		$siteName = trim($_POST['title']);
		$description = trim($_POST['description']);
		if(empty($siteName))
			$error = 'Website title cannot be empty';
		else if(empty($description))
			$error = 'Website description cannot be empty';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				//will throw an error as the website's name and creator are PK meaning there cannot be any duplicates
				$sql = 'INSERT INTO websites (creator,name, description) VALUES (:email,:title,:desc);';
				$cmd = $db->prepare($sql);
				$email = $_SESSION['email'];
				$cmd->bindParam(':email' , $email, PDO::PARAM_STR, 128);
				$cmd->bindParam(':title', $siteName, PDO::PARAM_STR, 35);
				$cmd->bindParam(':desc', $description, PDO::PARAM_STR, 600);
				$cmd->execute();
				/*there is a trigger in db that will add this website and creator to website_admin table to indicate that the creator is also an
				admin allowed to edit the site and content as such
				**I made a trigger instead of the below code as I think it'll be more efficient and that you're marking PHP not MySQL
				$sql = 'INSERT INTO websites_admin (name,creator, creator) VALUES (:title,:email,:email);';
				$cmd = $db->prepare($sql);
				$email = $_SESSION['email'];
				$cmd->bindParam(':email' , $email, PDO::PARAM_STR, 128);
				$cmd->bindParam(':title', $siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				*/
				header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=1");
				exit();
			}
			catch(Exception $exception)
			{
				//check if the error was thrown because they already have a website with that name
				$sql='SELECT name FROM websites WHERE creator=:email AND name = :siteName';
				$cmd=$db->prepare($sql);
				$cmd->bindParam(':email', $_SESSION['email'],PDO::PARAM_STR,128);
				$cmd->bindParam(':siteName',$siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$duplicate = $cmd->fetch();
				if(!empty($duplicate))
					$error = 'You already have a webpage with that name';
				header("location:create.php?error=$error");
				exit();
			}
		}
	}
	else if($step==2)
	{
		$pageNumber = $_GET['pageNumber'];
		$pageTitle = trim($_POST['pageTitle']);
		$siteName = $_GET['siteTitle'];
		$content = trim($_POST['pageContent']);
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
				$sql = 'INSERT INTO pages VALUES (:name,:siteName,:creator,:content,:pageNumber);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':name' , $pageTitle, PDO::PARAM_STR, 50);
				$cmd->bindParam(':content', $content, PDO::PARAM_STR, 10000);
				$cmd->bindParam(':creator', $_SESSION['email'], PDO::PARAM_STR, 128);
				$cmd->bindParam(':pageNumber',$pageNumber, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName',$siteName,PDO::PARAM_STR, 35);
				$cmd->execute();
				//increment the page number as to 'add' a new page
				$pageNumber= $pageNumber+1;
				header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=$pageNumber");
				exit();
			}
			catch(Exception $exception)
			{
				//check if the error was thrown because they already have a webpage with that name
				$sql='SELECT name FROM pages WHERE creator=:email AND siteName = :siteName';
				$cmd=$db->prepare($sql);
				$cmd->bindParam(':email', $_SESSION['email'],PDO::PARAM_STR,128);
				$cmd->bindParam(':siteName',$siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$duplicate = $cmd->fetch();
				if($duplicate['name']==$pageTitle)
					$error = 'You already have a webpage with that name';
				$error = 'You already have a webpage with that name';
				header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
				exit();
			}
		}
		header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
		exit();
	}
	else if($step==3)
	{
		$siteName = $_GET['siteTitle'];
		header("location:publish.php?siteTitle=$siteName");
		exit();
	}
	else
	{
		//todo: add appropriate redirect when a site page is clicked to edit it
		//todo: update and add publish column to site allow viewing it in when opened
		header('location:menu.php');
		exit();
	}
	?>