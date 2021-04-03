<?php
	require_once 'authenticate.php';
	$title = 'Main menu';
	require_once 'meta.php';
	require_once 'menu-options.php';
?>
	<h2>Your websites</h2>
	<ul>
		<?php
			$email = $_SESSION['email'];
			require_once 'connect.php';
			//query db for existing website created by this (logged in) user
			$sql = 'SELECT name, creatorID FROM websites WHERE creator=:email;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$db = null;
			$websites = $cmd->fetchAll();
			//display each of user's website with appropriate delete and edit buttons
			foreach($websites as $site)
				echo '<li><a target="_blank" href="mySite.php?pg=0&site' . $site['name'] . '&ID=' . $site['creatorID'] . '">' . $site['name'] . '</a>
					  <a href="edit.php?siteTitle=' . $site['name'] . '"><button class="btn btn-dark" type="button">Edit</button></a>
					  <a href="delete.php?siteTitle=' . $site['name'] . "&creator=$email\"" . ' onclick="return confirmDelete()"><button class="btn btn-danger" type="button">Delete</button></a></li>';
		?>
		<li><a href="create.php">
				<button class="btn btn-success">+</button>
			</a></li>
	</ul>
	<h2>Collaborating sites</h2>
	<ul>
		<?php
			require 'connect.php';
			//Query db for website in which they are a collaborator (not the creator) an admin
			$sql = 'SELECT name, w.creator, creatorID FROM websites_admin AS wa
    				INNER JOIN websites AS w on wa.siteName = w.name
					WHERE admin=:email AND wa.creator != :email AND w.creator = wa.creator;';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
			$cmd->execute();
			$db = null;
			$websites = $cmd->fetchAll();
			//display all website in which they are an admin with appropriate edit buttons
			//they do not display a delete button as only the creator should delete the website
			foreach($websites as $site)
				echo '<li><a target="_blank" href="mySite.php?pg=0&site' . $site['name'] . '&ID=' . $site['creatorID'] . '">' . $site['name'] . '</a>' .
						$site['siteName'] . ' <a href="edit.php?siteTitle=' . $site['siteName'] . '&creator='
						. $site['creator'] . '"><button class="btn btn-dark" type="button">Edit</button></a></li>';
		?>
	</ul>
<?php
	require_once 'footer.php' ?>