<?php
	$title = $_GET['title'];
	$pages = $_GET['pages'];
	$pageNumber = $_GET['pageNumber'];
	$webID = $_GET['webID'];
	//to hold details of current page from db if any
	$pageDetails = "";
	//check if passed in page number is present, numeric, and greater than 1
	if(empty($pageNumber) || !is_numeric($pageNumber) || $pageNumber < 1)
		$pageNumber = 1;
	require_once 'connect.php';
	//create specified number of pages of they are creating a new website.
	//Or get pages from databse if they are editing an existing one
	if(empty($webID))
	{
		//get the websites ID if not present in url/GET array
		$sql = 'SELECT ID FROM websites WHERE name =:title;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':title', $title, PDO::PARAM_STR, 35);
		$cmd->execute();
		$webID = $cmd->fetch();
		for($i = 1; $i <= $pages; $i++)
		{
			$sql = "INSERT INTO pages VALUES ('','',:webID,$i);";
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':webID', $webID['ID'], PDO::PARAM_INT, 11);
			$cmd->execute();
		}
	}
	else
	{
		$sql = 'SELECT title, content FROM pages WHERE websiteID = :webID AND pageNumber = :pageNum;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
		$cmd->bindParam(':pageNum', $pageNumber, PDO::PARAM_INT, 11);
		$cmd->execute();
		$pageDetails = $cmd->fetch();
	}
	require_once 'meta.php';
?>
	<form action="website-validation.php?pageNumber="<?php echo "$pageNumber"; ?> method="post">
		<label for="pageTitle">Page title</label>
		<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1"
		       value="<?php if(!empty($pageDetails)) echo $pageDetails['title']; ?>)" />
		<label for="pageContent">Page content</label>
		<textarea name="pageContent" id="pageContent" required
		          placeholder="HTML content is allowed i.e. <h1>Hello</h1><p>Welcome to my page, I hope you enjoy</p>..."><?php
				if(!empty($pageDetails))
					echo $pageDetails['content'];
			?>
			?></textarea>
		<button type="submit">Submit</button>
	</form>
<?php
	require_once 'footer.php';
?>