<?php
	require_once 'authenticate.php';
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	$pageNumber = $_GET['pageNumber'];
	if(empty($pageNumber))
	{
		require 'connect.php';
		$sql = 'DELETE FROM websites WHERE name = :siteName AND creator = :creator;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$db=null;
		header("location:menu.php?msg=Successfully deleted $siteName website.");
		exit();
	}
	else
	{
		try
		{
			$error = "in delete";
		require 'connect.php';
		$sql = 'DELETE FROM pages WHERE siteName = :siteName AND creator = :creator AND pageNumber = :pgNum;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->bindParam(':pgNum', $pageNumber, PDO::PARAM_INT, 11);
		$error = $cmd->execute();
		//reorder the pages accordingly
		//I.e. if the 3rd page was taken out of 5 reorder page 4 and 5,
		//to 3 and 4 respectively
		$sql = 'UPDATE pages SET pageNumber = pageNumber-1 WHERE siteName = :siteName AND creator = :creator AND pageNumber > :pgNum;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->bindParam(':pgNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->execute();
		$db=null;
		header("location:edit-webpages.php?msg=Successfully deleted page $pageNumber from website.&creator=$creator&siteTitle=$siteName&pageNumber=1");
		exit();
		}
		catch(Exception $exception){
			header("location:edit-webpages.php?msg=Successfully deleted page $pageNumber from website.&creator=$creator&siteTitle=$siteName&pageNumber=1&error=$error");
			exit();
		}
	}
?>