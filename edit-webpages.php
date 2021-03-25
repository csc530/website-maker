<?php
	$title = 'Page Content';
	$redirect = true;
	require_once 'meta.php';
	$error = $_GET['error'];
	$title = $_GET['title'];
	$pageNumber = $_GET['pageNumber'];
	$webID = $_GET['webID'];
	//to hold details of current page from db if any
	$pageDetails = "";
	//if there is no error get check for pre-existing page content from db
	if(empty($error))
	{
		//check if passed in page number is present, numeric, and greater than 1
		if(empty($pageNumber) || !is_numeric($pageNumber) || $pageNumber < 1)
			$pageNumber = 1;
		require 'connect.php';
		//if the webpage's website ID is not given in the url get it from the db
		if(empty($webID))
		{
			//get the websites ID from db
			//this will work (with fetch) as each user must have a unique website name for each of their sites
			$sql = 'SELECT ID FROM websites WHERE name = :title AND creator = :email;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':title', $title, PDO::PARAM_STR, 35);
			$cmd->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR, 128);
			$cmd->execute();
			$webID = $cmd->fetch();
			$webID = $webID['ID'];
		}
		try
		{
			$sql = 'SELECT title, content FROM pages WHERE websiteID = :webID AND pageNumber = :pageNum;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
			$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
			$cmd->execute();
			$db=null;
			$pageDetails = $cmd->fetch();
		}
		catch(Exception $exception)
		{
		}
	}
	//if there is an error display it and reload previously entered data into the form
	else
	{
		//declare an array pageDetails as an array with string indicies (title and content) with the pertinent values from the GET url
		$pageDetails = array('title' => $_GET['pageTitle'], 'content' => $_GET['content']);
	}
	try
	{
		require 'connect.php';
		$sql = 'SELECT pageNumber, websiteID FROM pages WHERE websiteID = :webID AND pageNumber != :pageNum;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->execute();
		$pages = $cmd->fetchAll();
		if(!empty($pages))
			echo "<h2>pages</h2>\n<ul>";
		foreach($pages as $page)
			echo '<li><a href="edit-webpages.php?pageNumber=' . $page['pageNumber'] . '&webID=' . $page['websiteID'] . '">' . $page['title'] . '</a></li>';
		if(empty($pages))
			echo "</ul>";
	}
	catch(Exception $exception)
	{
	}
?>
	<form action="website-validation.php?pageNumber=<?php echo "$pageNumber&webID=$webID&pageTitle=$title"; ?>" method="post">
	<h1><?php echo "$title: page $pageNumber" ?></h1>
		<?php
			if(!empty($error))
				echo "<p class='alert alert-danger'>$error</p>";
		?>
	<label for="pageTitle">Page title</label>
	<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1"
	       value="<?php if(!empty($pageDetails)) echo $pageDetails['title']; ?>" />
	<label for="pageContent">Page content</label>
	<textarea name="pageContent" id="pageContent" required
	          placeholder="HTML content is allowed i.e. <h1>Hello</h1><p>Welcome to my page, I hope you enjoy</p>..."><?php
			if(!empty($pageDetails))
				echo $pageDetails['content'];
		?></textarea>
		<button type="submit" name="step" value="2">Add page</button>
	<button type="submit" name="step" value="3">Submit</button>
	</form>
<?php
	require_once 'footer.php';
?>