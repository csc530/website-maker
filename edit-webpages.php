<?php
	$title = 'Page Content';
	$redirect = true;
	require_once 'meta.php';
	//get errorMsg if any
	$error = $_GET['error'];
	//get website's title
	$siteName = $_GET['siteTitle'];
	//get webpage's number
	$pageNumber = $_GET['pageNumber'];
	//to hold details of current page from db if any
	$pageDetails = "";
	//check if a creator has been if not then the logged in user is the creator of the site
	$creator = $_GET['creator'];
	if(empty($creator))
		$creator = $_SESSION['email'];
	//if there is no error check for pre-existing page content from db
	if(empty($error))
	{
		require 'connect.php';
		try
		{
			//get the details of current page if it already exits in db else this will do nothing
			$sql = 'SELECT name, content FROM pages WHERE creator = :creator AND pageNumber = :pageNum AND siteName = :siteName;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
			$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
			$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
			$cmd->execute();
			$db = null;
			//can use fetch as their should (as per db constraints) one page that matches the pageNumber
			$pageDetails = $cmd->fetch();
		}
		catch(Exception $exception)
		{
		}
	}
	//if there is an error display it and reload previously entered data into the form
	else
	{
		//declare an array pageDetails as an array with string indices (title and content) with the pertinent values from the GET url
		$pageDetails = array('name' => $_GET['pageTitle'], 'content' => $_GET['content']);
	}
	try
	{
		//display other pages on site on top in a list (not including currently built site)
		require 'connect.php';
		$sql = 'SELECT pageNumber, name FROM pages WHERE creator = :creator AND pageNumber != :pageNum AND siteName=:siteName ORDER BY pageNumber;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$pages = $cmd->fetchAll();
		//print each page as a list item if any
		if(!empty($pages))
		{
			echo "<h2>pages</h2>\n<ol>";
			foreach($pages as $page)
				echo '<li>'.$page['pageNumber'].'. <a href="edit-webpages.php?siteTitle=' . $siteName . '&pageNumber=' . $page['pageNumber'] . '">' .
						$page['name'] .
				'</a></li>';
			echo "</ol>";
		}
	}
	catch(Exception $exception)
	{
	}
?>
	<form action="website-validation.php?pageNumber=<?php echo "$pageNumber&siteTitle=$siteName&creator=$creator"; ?>"
	      method="post">
		<h1><?php echo "$siteName: page $pageNumber" ?></h1>
		<?php
			if(!empty($error))
				echo "<p class='alert alert-danger'>$error</p>";
		?>
		<label for="pageTitle">Page title</label>
		<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1"
		       value="<?php if(!empty($pageDetails)) echo $pageDetails['name']; ?>" />
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