<?php
	$title = 'Sites';
	require_once 'meta.php';
	?>
	<h1>Websites created with Dreamscapes: </h1>
	<h2>Featured sites: </h2>
	<ol>
<?php
	require_once 'connect.php';
	$sql = 'SELECT preview, name FROM websites ORDER BY visits LIMIT 10;';
	$cmd = $db->prepare($sql);
	$cmd->execute();
	$sites =$cmd->fetchAll();
	foreach($sites as $site)
		echo '<li title="'.$site['name'].'"><figure>'.$site['preview'].'<figcaption>'.$site['name'].'</figcaption></figure></li>';
	?>
	</ol>
	<form action="hosted-sites.php" method="post">
	<h2>Search</h2>
		<label for="search">Website name: </label>
	<input type="text" id="search" name="search" required />
		<button type="submit" class="btn-primary btn">Search</button>
	</form>
		<?php
	if(!empty($_POST['search']))
	{
		require 'connect.php';
		$sql='SELECT name, preview FROM websites WHERE name LIKE %:search%;';
	}
	require_once 'footer.php';
?>