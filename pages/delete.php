<?php
	require_once 'authenticate.php';
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	$pageNumber = $_GET['pageNumber'];
	//if there is no pageNumber given that means they are deleting a website not a webpage
	if(empty($pageNumber))
	{
		try
		{
			require_once 'connect.php';
			//delete website belonging to the user,
			$sql = 'DELETE FROM websites WHERE name = :siteName AND creatorID = :creator;';
			$cmd = $db->prepare($sql);
			//binds the :creator placeholder as the session's email instead of $_GET's creator as only the original creator AKA the signed in user can
			//delete the site not just any admin
			$cmd->bindParam(':creator', $_SESSION['id'], PDO::PARAM_STR, 128);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->execute();
			$db = null;
			header("location:menu.php?msg=Successfully deleted $siteName website.");
			exit();
		}
		catch(Exception $exception)
		{
			header("location:menu.php?error=Error, please try again.");
			exit();
		}
	}
	else
	{
		try
		{
			require_once 'connect.php';
			$sql = 'DELETE FROM pages WHERE siteName = :siteName AND creatorID = :creator AND pageNumber = :pgNum;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->bindParam(':pgNum', $pageNumber, PDO::PARAM_INT, 11);
			$cmd->execute();
			//reorder the pages accordingly
			//I.e. if the 3rd page was taken out of 5 reorder page 4 and 5,
			//to 3 and 4 respectively
			$sql = 'UPDATE pages SET pageNumber = pageNumber-1 WHERE siteName = :siteName AND creatorID = :creator AND pageNumber > :pgNum;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->bindParam(':pgNum', $pageNumber, PDO::PARAM_INT, 11);
			$cmd->execute();
			$db = null;
			header("location:edit-webpages.php?msg=Successfully deleted page $pageNumber from website.&creator=$creator&siteTitle=$siteName&pageNumber=1");
			exit();
		}
		catch(Exception $exception)
		{
			header("location:edit-webpages.php?error=Error, please try again.");
			exit();
		}
	}
?>