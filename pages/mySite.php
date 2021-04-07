<?php
	//todo: add updated comments on how email is now unique with ID being PK
	//variable needed to find and display correct page content
	$ID = $_GET['ID'];
	$site = $_GET['site'];
	$pg = $_GET['pg'];
	//if there is no given page number display their splash/overview/welcome page
	if(empty($pg) || $pg == 0)
	{
		//used aliases to display content at bottom simpler, less ifs
		$sql = "SELECT 'Welcome' AS name, description AS 'content' FROM websites WHERE name = :name";
		$pg = 0;
	}
	else
		$sql = 'SELECT name, content FROM pages WHERE siteName = :name AND pageNumber = :pgNum';
	//if no id is given look for specific pages with given id
	if(!empty($ID))
		$sql .= ' AND creatorID = :id';
	try
	{
		require_once 'connect.php';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':name', $site, PDO::PARAM_STR, 35);
		if(!empty($ID))
			$cmd->bindParam(':id', $ID, PDO::PARAM_INT, 11);
		if($pg != 0)
			$cmd->bindParam(':pgNum', $pg, PDO::PARAM_INT, 11);
		$cmd->execute();
		//in the case no id is given and there are multiple site only get the first one, they should have had the ID  ¯\_(ツ)_/¯
		$pageDetails = $cmd->fetch();
		//query db for all related pages of website, to be used as header as links
		$sql = 'SELECT name, pageNumber FROM pages WHERE siteName = :name AND pageNumber != :pgNum';
		if(!empty($ID))
			$sql .= ' AND creatorID = :id';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':name', $site, PDO::PARAM_STR, 35);
		$cmd->bindParam(':pgNum', $pg, PDO::PARAM_INT, 11);
		if(!empty($ID))
			$cmd->bindParam(':id', $ID, PDO::PARAM_INT, 11);
		$cmd->execute();
		$db = null;
		$links = $cmd->fetchAll();
	}
	catch(Exception $e)
	{
		//send a 404 directive so htaccess will handle it
		header('HTTP/poop', true, 404);
		exit();
	}
	//use website tilte in tab
	$title = $site;
	?>
	<!DOCTYPE html>
	<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo "$title"; ?></title>
	<!--todo paths: make absolute paths for below links-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<script src="../js/bootstrap.min.js" type="text/javascript" defer></script>
<!--User's sites style sheet-->
	<link href="../css/userStyles.css" type="text/css" rel="stylesheet" />
	<script src="../js/userScripts.js" type="text/javascript" defer></script>
</head>
	<body class="bg-light">
	<header>
		<?php
			echo '<h1>' . $pageDetails['name'] . '</h1>';
		?>
		<nav class='navbar navbar-expand-lg navbar-light'>
			<div class="container-fluid">
<?php
	//todo add include for nice bootstrap navbar and meta header
	echo "<span class='navbar-brand'>$site</span><div class='d-flex'><ul class='navbar-nav me-auto mb-2 mb-lg-0'><li><a class='nav-link' href='mySite.php?ID=$ID&site=$site&pg=0'>Home</a></li>";
	//create links foreach page in website
	foreach($links as $link)
		echo "<li class='nav-item'><a class='nav-link' href='mySite.php?ID=$ID&site=$site&pg=" . $link['pageNumber'] . "'>" . $link['name'] . '</a></li>';
	echo '</ul></div>';
	?>
			</div>
		</nav>
	</header>
	<main class="container">
	<?php
	//write page appropriately if using page or main website table, depends if pgnum is empty (just changes the variables names)
	//write page content as HTML
	echo $pageDetails['content'];
	?>
	</main>
	<footer class="modal-footer"><small>Created with <a href="index.php" target="_blank" rel="">Web Dreamscapes</a>&copy;.</small></footer>
	</body>
</html>
<?php
	require 'connect.php';
	//increase the number if visits of the website
	//todo if possible add 1 visit per session
	$sql='UPDATE websites SET visits = visits+1 WHERE name = :name';
	if(!empty($ID))
		$sql.=' AND creatorID = :id;';
	$cmd=$db->prepare($sql);
	$cmd->bindParam(':name', $site, PDO::PARAM_STR, 35);
	if(!empty($ID))
	$cmd->bindParam(':id', $ID, PDO::PARAM_INT, 11);
	$cmd->execute();
	$db = null;
		?>