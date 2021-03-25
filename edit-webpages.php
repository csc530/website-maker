<?php
	$title = $_GET['title'];
	$pages = $_GET['pages'];
	$pageNumber = $_GET['pageNumber'];
	//check if passed in page number is present, numeric, and greater than 1
	if(empty($pageNumber)||!is_numeric($pageNumber)||$pageNumber<1)
		$pageNumber=1;
	require_once 'connect.php';
	$sql = 'SELECT ID FROM websites WHERE name =:title;';
	$cmd= $db->prepare($sql);
	$cmd->bindParam(':title',$title,PDO::PARAM_STR,35);
	$cmd->execute();
	$webID = $cmd->fetch();
	for($i = 1; $i<=$pages;$i++)
	{
		$sql = "INSERT INTO pages VALUES ('','',:webID,$i);";
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':webID', $webID, PDO::PARAM_INT, 11);
		$cmd->execute();
	}
	require_once 'meta.php';
?>
<form action="website-validation.php?pageNumber="<?php echo "$pageNumber"; ?> method="post">
	<label for="pageTitle">Page title</label>
	<input id="pageTitle" name="pageTitle" type="text" required max="50" min="1" />
	<label for="pageContent">Page content</label>
	<textarea name="pageContent" id="pageContent" required placeholder="HTML content is allowed i.e. <h1>Hello</h1><p>Welcome to my page, I hope you enjoy</p>..."></textarea>
	<button type="submit">Submit</button>
</form>
<?php
	require_once 'footer.php';
	?>