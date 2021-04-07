<?php
	require_once 'authenticate.php';
	$title = 'Page Content';
	require_once 'meta.php';
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
		$creator = $_SESSION['id'];
	//check for pre-existing page content from db
	require 'connect.php';
	try
	{
		//get the details of current page if it already exits in db else this will do nothing
		$sql = 'SELECT name, content FROM pages WHERE creatorID = :creator AND pageNumber = :pageNum AND siteName = :siteName;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$db = null;
		//can use fetch as their should (as per db constraints) one page that matches the pageNumber
		$pageDetails = $cmd->fetch();
		//if there is a pre-exiting page then display to user that they are updating rather than adding
		if(!empty($pageDetails))
			$buttonMsg = 'Update';
		else
			$buttonMsg = 'Add page';
	}
	catch(Exception $exception)
	{
		//declare an array pageDetails as an array with string indices (title and content) with the pertinent values from the GET url(if any)
		//since the db query failed
		$pageDetails = array('name' => $_GET['pageTitle'], 'content' => $_GET['content']);
		$buttonMsg = 'Add page';
	}
	//if there is an error display it and reload previously entered data into the form
	if(!empty($error))
	{
		//declare an array pageDetails as an array with string indices (title and content) with the pertinent values from the GET url
		$pageDetails = array('name' => $_GET['pageTitle'], 'content' => $_GET['content']);
		$buttonMsg = 'Add page';
	}
	try
	{
		//display other pages on site on top in a list (not including current page)
		require 'connect.php';
		$sql = 'SELECT pageNumber, name FROM pages WHERE creatorID = :creator AND pageNumber != :pageNum AND siteName=:siteName ORDER BY pageNumber;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$db = null;
		$pages = $cmd->fetchAll();
		//print each page as a list item if any with a link to go and edit that page
		if(!empty($pages))
		{
			echo "<h2>pages</h2>\n<ul>";
			foreach($pages as $page)
				echo '<li>' . $page['pageNumber'] . '. <a href="edit-webpages.php?siteTitle=' . $siteName . '&pageNumber=' . $page['pageNumber'] . '">' .
						$page['name'] .
						'</a><a href="delete.php?pageNumber=' . $page['pageNumber'] . "&siteTitle=$siteName&creator=$creator" . '" onclick="return confirmDelete()"><button class="btn btn-danger">Delete</button></a></li>';
			echo "</ul>";
		}
	}
	catch(Exception $exception)
	{
	}
?>
	<form action="website-validation.php?pageNumber=<?php
		echo "$pageNumber&siteTitle=$siteName&creator=$creator"; ?>"
	      method="post">
		<h1><?php
				echo "$siteName: page $pageNumber" ?></h1>
		<?php
			require_once 'msgOrError.php';
		?>
		<label for="pageTitle">Page title</label>
		<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1"
		       value="<?php
			       if(!empty($pageDetails)) echo $pageDetails['name']; ?>" />
		<label for="pageContent">Page content</label>
		<textarea name="pageContent" id="pageContent" required
		          placeholder="HTML content is allowed i.e. <h1>Hello</h1><p>Welcome to my page, I hope you enjoy</p>..."><?php
				if(!empty($pageDetails))
					echo $pageDetails['content'];
			?></textarea>
		<button type="submit" name="step" value="2"><?php
				echo $buttonMsg; ?></button>
		<button type="submit" name="step" value="3">Submit</button>
	</form>
<?php
	require_once 'footer.php';
?>