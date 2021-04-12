<?php
	$creatorID = $_GET['creator'];
	$siteName = $_GET['site'];
	if(!is_numeric($creatorID) || empty($siteName))
	{
		header('location:err.php');
		exit();
	}
	try
	{
		//Set the default blank logo if they chose to clear it
		if(!empty($_POST['clear']))
		{
			$path = '../images/Blank.svg';
			require 'connect.php';
			$sql = 'UPDATE websites SET logo = :logo WHERE creatorID = :creator AND name = :siteName;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':logo', $path, PDO::PARAM_STR, 120);
			$cmd->bindParam(':creator', $creatorID, PDO::PARAM_INT, 11);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->execute();
			$db = null;
		}
		else
		{
			//validate logo first, if any, so website isn't made, then redirected back. This prevents errors (creating website with
			// preexisting name,etc.)
			if(!empty($_FILES['logo']['name']))
			{
				//check if file name is too big db, (kind of arbitrary but shorter name = faster processes relating to logo so ¯\_(ツ)_/¯) if so
				// redirect back to create.php
				if(strlen($_FILES['logo']['name']) > 100)
				{
					header("location:create.php?error=Logo file name is too long, must be less than 100 characters.");
					exit();
				}
			}
			if(!empty($_FILES['logo']['name']))
			{
				$validTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
				//validate image, that it's mime type is of the valid image mime types above (png, jpg, gif, svg)
				if(in_array(mime_content_type($_FILES['logo']['tmp_name']), $validTypes))
				{
					$path = "../logos/$creatorID~" . $_FILES['logo']['name'];
					move_uploaded_file($_FILES['logo']['tmp_name'], $path);
					//naming convention of file is just the creator's ID + original name, if they upload file with same name it'll replace and
					// I'm ok with that
					require 'connect.php';
					$sql = 'UPDATE websites SET logo = :logo WHERE creatorID = :creator AND name = :siteName;';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':logo', $path, PDO::PARAM_STR, 120);
					$cmd->bindParam(':creator', $creatorID, PDO::PARAM_INT, 11);
					$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
					$cmd->execute();
					$db = null;
				}
			}
		}
	}
	catch(Exception $exception){
		$return="edit.php?siteTitle=$siteName&creator=$creatorID";
		header("location:err.php?return=$return");
		exit();
	}
	//return to the edit page, upload was successful
	header("location:edit.php?siteTitle=$siteName&creator=$creatorID");
	exit();
	?>