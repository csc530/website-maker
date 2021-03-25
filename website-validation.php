<?php
	$redirect = true;
	require_once 'authenticate.php';
	$step = $_POST['step'];
	$error = 'Please try again';
	//check what step of the website building process user is on
	if($step==1)
	{
		$title = $_POST['title'];
		$description = $_POST['description'];
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
				header("location:edit-webpages.php?title=$title");
			}
			catch(Exception $exception)
			{
				header("location:create.php?error=$error");
			}
		}
	}
	?>