<?php
	$step = $_POST['step'];
	$error = 'Please try again';
	//check what step of the website building process user is on
	if($step==1)
	{
		$title = $_POST['title'];
		$description = $_POST['description'];
		$pages = $_POST['pages'];
		if(empty($title))
			$error = 'Website title cannot be empty';
		else if(empty($description))
			$error = 'Website description cannot be empty';
		else if(empty($pages) || !is_numeric($pages) || $pages < 1)
			$error = 'The number of pages must be greater than 1.';
		else
		{
			try
			{
				$error = 'Network error, please try again.';
				require_once '../page-includes/connect.php';
				$sql = 'INSERT INTO websites (creator,name, description) VALUES (:email,:title,:desc);';
				$cmd = $db->prepare($sql);
				//todo:get email from session
				$cmd->bindParam(':email' , $email, PDO::PARAM_STR, 128);
				$cmd->bindParam(':title', $title, PDO::PARAM_STR, 35);
				$cmd->bindParam(':desc', $description, PDO::PARAM_STR, 600);
				$cmd->execute();
				header("location:edit-page.php?title=$error;pages=$pages");
			}
			catch(Exception $exception)
			{
				header("location:create.php?error=$error");
			}
		}
	}
	?>