<?php
	//todo: add updated comments on how email is now unique with ID being PK
	//variable needed to find and display correct page content
	$ID = $_GET['ID'];
	$site = $_GET['site'];
	$pg = $_GET['pg'];
	echo "site=$site, pg =$pg, Id= $ID";
	//I use ID instead of the creator's/admin's email for security and safety so they don't put their email on blast when sharing their website
	//if there is no given page numbe display their splash/overview/welcome page
	if(empty($pg) || $pg == 0)
	{
		//used aliases to display content at bottom simpler, less ifs
		$sql = "SELECT 'Welcome' AS name, description AS `content` FROM websites WHERE name = :name ";
		$pg=0;
	}
	else
		$sql = 'SELECT name, content FROM pages WHERE siteName = :name AND pageNumber = :pgNum';
	if(!empty($ID))
		$sql .= 'AND creatorID = :id';
	echo $sql;
	try
	{
		require_once 'connect.php';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':name', $site, PDO::PARAM_STR, 35);
		if(!empty($ID))
		$cmd->bindParam(':id', $ID, PDO::PARAM_INT, 11);
		if(!empty($pg))
		$cmd->bindParam(':pgNum', $pg, PDO::PARAM_INT, 11);
		$cmd->execute();
		//in the case no id is given and there are multiple site only get the first one, they should have had the ID  ¯\_(ツ)_/¯
		$pageDetails = $cmd->fetch();
		//query db for all related pages of website, to be used as header as links
		$sql = 'SELECT name, pageNumber FROM pages WHERE siteName = :name AND pageNumber != :pgNum';
		$cmd=$db->prepare($sql);
		if(!empty($ID))
			$sql .= 'AND creatorID = :id';
		$cmd->bindParam(':name', $site, PDO::PARAM_STR, 35);
			$cmd->bindParam(':pgNum', $pg, PDO::PARAM_INT, 11);
		$cmd->execute();
		$db =null;
		$links = $cmd->fetchAll();
	}
	catch(Exception $e)
	{
		//send a 404 directive so htaccess will handle it
		header('HTTP/404', true, 404);
		exit();
	}
	//use website tilte in tab
	$title = $site;
	//todo add include for nice bootstrap navbar
	echo "<nav class='nav navbar navbar-expand-lg'>$site<ul><li><a class='nav-link' href='mySite.php?ID=$ID&site=$site&pg=0'>Home</a></li>";
	//create links foreach page in website
	foreach($links as $link)
		echo "<li><a class='nav-link' href='mySite.php?ID=$ID&site=$site&pg=".$link['pageNumber']."'>".$link['name'].'</a></li>';
	echo '</ul></nav>';
	//write page appropriately if using page or main website table, depends if pgnum is empty (just changes the variables names)
	//write page content as HTML
		echo '<h1>'.$pageDetails['name'].'</h1>';
		echo $pageDetails['content'];