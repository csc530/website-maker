<?php
	//TODO: validate session or redirect
	$title = 'Main menu';
	require_once 'header.php';
?>
	<h1>Welcome<!--Add name arg for l8r aswell as a bunch of profile stuffies too--></h1>
	<nav>
		<ul>
			<li><a href="create.php">New website</a></li>
			<!--Probably a JS alert box choosing available websites-->
			<li>Edit Websites</li>
			<li><a href="profile.php">profile</a></li>
		</ul>
	</nav>
	<h2>Your websites</h2>
	<ul>
		<?php
		//populate ul with website hrefs from database
			require_once 'connect.php';
			$sql = 'SELECT name, preview, addr FROM websites
    				INNER JOIN websites_admin ON websites.ID = websites_admin.websiteID
					WHERE admin=:email;';
			$cmd = $db->prepare($sql);
			//TODO: somehow get email from session
			$cmd->bindParam(':email',$email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$db=null;
			$websites = $cmd->fetchAll();
			foreach($websites as $site)
				echo '<li><a href="'.$site['addr'].'">'.$site['preview'].'</a></li>';
		?>
		<li><a href="create.php">+</a></li>
	</ul>
<?php require_once 'footer.php' ?>