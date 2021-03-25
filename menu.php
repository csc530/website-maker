<?php
	$redirect = true;
	$title = 'Main menu';
	require_once 'meta.php';
	require_once 'menu-header.php';
?>
	<h2>Your websites</h2>
	<ul>
		<?php
		//populate ul with website hrefs from database
			require_once 'connect.php';
			$sql = 'SELECT name, preview, addr FROM websites
    				INNER JOIN websites_admin ON websites.ID = websites_admin.websiteID
					WHERE admin=:email;';
			$cmd = $db->prepare($sql);
			$email = $_SESSION['email'];
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