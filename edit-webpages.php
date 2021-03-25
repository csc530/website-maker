<?php
	$title = 'Page Content';
	require_once 'meta.php';
	$title = $_GET['title'];
	$pageNumber = $_GET['pageNumber'];
	$webID = $_GET['webID'];
	//to hold details of current page from db if any
	$pageDetails = "";
	//check if passed in page number is present, numeric, and greater than 1
	if(empty($pageNumber) || !is_numeric($pageNumber) || $pageNumber < 1)
		$pageNumber = 1;
	require_once 'connect.php';
	//create specified number of pages of they are creating a new website.
	//Or get pages from database if they are editing an existing one
	if(empty($webID))
	{
		$sql = 'SELECT title, content FROM pages WHERE websiteID = :webID AND pageNumber = :pageNum;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->execute();
		$pageDetails = $cmd->fetch();
	}
?>
	<form action="website-validation.php?pageNumber="<?php echo "$pageNumber"; ?> method="post">
		<label for="pageTitle">Page title</label>
		<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1"
		       value="<?php if(!empty($pageDetails)) echo $pageDetails['title']; ?>" />
		<label for="pageContent">Page content</label>
		<textarea name="pageContent" id="pageContent" required
		          placeholder="HTML content is allowed i.e. <h1>Hello</h1><p>Welcome to my page, I hope you enjoy</p>..."><?php
				if(!empty($pageDetails))
					echo $pageDetails['content'];
			?></textarea>
		<button type="submit">Submit</button>
	</form>
<?php
	require_once 'footer.php';
?>