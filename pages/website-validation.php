<?php
	require_once 'authenticate.php';
	$step = $_POST['step'];
	$error = 'Please try again';
	if(empty($_GET['creator']))
	$creatorID = $_SESSION['id'];
	else
		$creatorID = $_GET['creator'];
		$creator = $_SESSION['email'];
	//check what step of the website building process user is on
	//if user is coming from create.php (creating website title and description)
	if($step == 1)
	{
		//trim whitespace from website title and description
		$siteName = trim($_POST['title']);
		$description = trim($_POST['description']);
		//basic validation of website name and description
		if(empty($siteName))
			$error = 'Website title cannot be empty';
		else if(empty($description))
			$error = 'Website overview cannot be empty';
		//I choose for 'Hi' as a basic and simplest overview
		else if(strlen($description) < 2)
			$error = 'Website overview must at least be 2 characters';
		//I think anything under 5 is impractical
		else if(strlen($siteName) < 3)
			$error = 'Website name/title must at least be 3 characters';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				//will throw an error as the website's name and creator are PK meaning there cannot be any duplicates
				$sql = 'INSERT INTO websites (name, description, creatorID) VALUES (:title, :desc, :creatorID);';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':title', $siteName, PDO::PARAM_STR, 35);
				$cmd->bindParam(':desc', $description, PDO::PARAM_STR, 600);
				$cmd->bindParam(':creatorID', $creatorID, PDO::PARAM_INT, 11);
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
				header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=1&creator=$creatorID");
				exit();
			}
			catch(PDOException $exception)
			{
				//check if the error was thrown because they already have a website with that name
				$sql = 'SELECT name FROM websites WHERE creatorID=:creator AND name = :siteName';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':creator', $creatorID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$duplicate = $cmd->fetch();
				if(!empty($duplicate))
					$error = 'You already have a webpage with that name';
				$error.=$exception[0][0];
				header("location:create.php?error=$error");
				exit();
			}
		}
		//redirect for invalid title or description
		header("location:create.php?error=$error");
		exit();
	}
	//check for individual website's pages
	else if($step == 2)
	{
		$pageNumber = $_GET['pageNumber'];
		$pageTitle = trim($_POST['pageTitle']);
		$siteName = $_GET['siteTitle'];
		$content = trim($_POST['pageContent']);
		//validate page title and content
		if(empty($pageTitle))
			$error = 'Page title cannot be empty';
		else if(empty($content))
			$error = 'Page content cannot be empty';
		else
		{
			try
			{
				//verify user can edit this page
				require_once 'canEdit.php';
				$error = 'Network error, please try again.';
				require_once 'connect.php';
				//check if there if they are updating an existing page
				$sql = 'SELECT name FROM pages WHERE siteName=:siteName AND pageNumber=:pageNumber AND creatorID=:creatorID;';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':creatorID', $creatorID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':pageNumber', $pageNumber, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$isUpdate = $cmd->fetch();
				//if the fetch is empty then there is no existing page at that number so insert into db if not update (page of that pageNumber)
				//if statement changing SQL query as (you can see) the following code is the same
				if(empty($isUpdate))
					$sql = 'INSERT INTO pages VALUES (:name,:siteName,:content,:pageNumber, :creatorID);';
				else
					$sql = 'UPDATE pages SET content = :content, name = :name WHERE creatorID = :creatorID AND pageNumber = :pageNumber AND siteName = :siteName;';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':name', $pageTitle, PDO::PARAM_STR, 50);
				$cmd->bindParam(':content', $content, PDO::PARAM_STR, 10000);
				$cmd->bindParam(':creatorID', $creatorID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':pageNumber', $pageNumber, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$db=null;
				//increment the page number as to 'add' a new page (or progress to new page if they updated)
				$pageNumber = $pageNumber + 1;
				header("location:edit-webpages.php?siteTitle=$siteName&pageNumber=$pageNumber&creator=$creatorID");
				exit();
			}
			catch(Exception $exception)
			{
				//check if the error was thrown because they already have a webpage with that name
				$sql='SELECT name FROM pages WHERE creatorID=:ID AND siteName=:siteName AND name=:pageTitle;';
				$cmd=$db->prepare($sql);
				$cmd->bindParam(':ID', $creatorID, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
				$cmd->bindParam(':pageTitle', $pageTitle,PDO::PARAM_STR,50);
				$cmd->execute();
				$duplicate = $cmd->fetch();
				if($duplicate['name'] == $pageTitle)
					$error = 'There already is a webpage with that name.';
				header("location:edit-webpages.php?siteTitle=$siteName&creator=$creatorID&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
				exit();
			}
		}
		//redirect for invalid page title or content
		header("location:edit-webpages.php?siteTitle=$siteName&creator=$creatorID&pageNumber=$pageNumber&pageTitle=$pageTitle&content=$content&error=$error&step=$step");
		exit();
	}
	//submission of website (review)
	else if($step == 3)
	{
		$siteName = $_GET['siteTitle'];
		//verify user can edit this page
		require_once 'canEdit.php';
		header("location:publish.php?siteTitle=$siteName&creator=$creatorID");
		exit();
	}
	else
	{
		//if they messed with the form and edited the step redirect to home page
		header('location:menu.php');
		exit();
	}
?>