<?php
	$newUser = $_POST['user'];
	$editPage = $_POST['edit'];
	$basic = $_POST['update'];
	$websiteID = $_POST['websiteID'];
	$addUser = $_POST['add'];
	if($editPage == 'true')
		header("location:edit-page.php?pageID=$websiteID");
	else if(!empty($addUser))
	{
		require_once '../connect.php';
		$sql = 'INSERT INTO websites_admin VALUES (:websiteID, :user);';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':websiteID',$websiteID,PDO::PARAM_INT, 11);
		//db side checking as user is a foreign key so that user must exist
		$cmd->bindParam(':user', $newUser, PDO::PARAM_STR, 128);
		$cmd->execute();
		//todo ui: pass get variable to allow the new user to be highlighted for a time or something
		header("location:edit.php");
	}
		?>