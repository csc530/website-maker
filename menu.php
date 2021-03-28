<?php
	$redirect = true;
	$title = 'Main menu';
	require_once 'meta.php';
	require_once 'menu-options.php';
?>
	<h2>Your websites</h2>
	<ul>
		<?php
			require_once 'connect.php';
			$sql = 'SELECT name FROM websites WHERE creator=:email;';
			$cmd = $db->prepare($sql);
			$email = $_SESSION['email'];
			$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$db = null;
			$websites = $cmd->fetchAll();
			//todo add view url to params/make view urls in db
			foreach($websites as $site)
				echo '<li><a href="#">' . $site['name'] . '</a>
					  <a href="edit.php?siteTitle=' . $site['name'] . '"><button class="btn btn-dark" type="button">Edit</button></a>
					  <a href="delete.php?siteTitle=' . $site['name'] . '" onclick="confirmDelete()"><button class="btn btn-danger" type="button">Delete</button></a></li>';
		?>
		<li><a href="create.php">+</a></li>
	</ul>
	<h2>Collaborating sites</h2>
	<ul>
		<?php
			require 'connect.php';
			$sql = 'SELECT siteName, w.creator FROM websites_admin AS wa INNER JOIN websites AS w on wa.siteName = w.name	WHERE admin=:email AND wa.creator != :email AND w.creator = wa.creator;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR, 128);
			$cmd->execute();
			$db = null;
			$websites = $cmd->fetchAll();
			foreach($websites as $site)
				echo '<li><a href="#">' . $site['name'] . '</a>' . $site['siteName'] . ' <a href="edit.php?siteTitle=' . $site['siteName'] . '&creator='
						. $site['creator'] . '"><button class="btn btn-dark" type="button">Edit</button></a></li>';
		?>
	</ul>
<?php
	require_once 'footer.php' ?>