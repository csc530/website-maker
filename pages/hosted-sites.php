<?php
	$title = 'Sites';
	require_once 'meta.php';
	$search = $_POST['search'];
?>
	<h1>Websites created with Dreamscapes</h1>
	<h2>Featured sites: </h2>
	<ol>
		<?php
			require_once 'connect.php';
			$sql = 'SELECT logo, name, creatorID FROM websites WHERE public = true ORDER BY visits LIMIT 10;';
			$cmd = $db->prepare($sql);
			$cmd->execute();
			$sites = $cmd->fetchAll();
			foreach($sites as $site)
				echo '<li title="' . $site['name'] . '"><a href="mySite.php?ID' . $site['creatorID'] . '&pg=0&site=' . $site['name'] . '" <figure><img src="' . $site['logo'] . '" alt="' . $site['name'] . '\'s logo" class="logo-lg"/><figcaption>' . $site['name'] . '</figcaption></figure></a></li>';
		?>
	</ol>
	<form action="hosted-sites.php" method="post">
		<h2>Search</h2>
		<label for="search">Website name: </label>
		<div class="input-group">
			<input type="text" id="search" name="search" required maxlength="35" value="<?php
				echo $search; ?>" />
			<button type="submit" class="btn-primary btn">Search</button>
		</div>
	</form>
<?php
	if(!empty($_POST['search']))
	{
		try
		{
			require 'connect.php';
			$sql = 'SELECT logo, name, creatorID FROM websites WHERE public = true AND name LIKE :search ORDER BY visits LIMIT 10;';
			$search = '%' . $search . '%';
			$cmd = $db->prepare($sql);
			$cmd->bindParam(':search', $search, PDO::PARAM_STR, 35);
			$cmd->execute();
			$db = null;
			$results = $cmd->fetchAll();
			if(!empty($results))
			{
				echo '<ul class="no-list side-by-side">';
				foreach($results as $site)
					echo '<li title="' . $site['name'] . '"><a href="mySite.php?ID' . $site['creatorID'] . '&pg=0&site=' . $site['name'] . '" <figure><img src="' . $site['logo'] . '" alt="' . $site['name'] . '\'s logo" class="logo-lg"/><figcaption>' . $site['name'] . '</figcaption></figure></a></li>';
				echo '</ul>';
			}
			else
				echo '<div class="alert alert-warning"><p class="p-0">No matching results were found.</p></div>';
		}
		catch(Exception $exception){
			echo $exception;
			echo $exception[0];
			echo $exception[1];
			echo $exception[2];
			echo $exception[3];
		}
	}
	require_once 'footer.php';
?>