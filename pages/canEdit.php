<?php
	require 'connect.php';
	//This page is just an extra layer of protection if someone manually types in the address to edit a website of someone else to edit it
	//query db for to see if logged in user has permission to edit given site (of siteName and given creator)
	$sql = 'SELECT admin FROM (SELECT * FROM websites_admin WHERE siteName = :siteName AND creator = :creator) AS `wa*` WHERE admin=:email;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
	$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
	$cmd->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR, 128);
	$cmd->execute();
	$success = $cmd->fetch();
	//if no results are returned then user doesn't have permission to edit the website
	if(empty($success))
	{
		//redirect to menu with error
		header("location:menu.php?error=You don't have permission to edit $siteName.");
		exit();
	}