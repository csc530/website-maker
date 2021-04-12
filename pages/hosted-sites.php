<?php
	$title = 'Sites';
	require_once 'meta.php';
	$search = $_POST['search'];
?>
<h1>Websites created with Dreamscapes</h1>
<h2>Featured sites: </h2>
<!--Bootstrap carousel element from https://getbootstrap.com/docs/5.0/components/carousel/#with-captions-->
<div id="carouselExampleCaptions" class="carousel slide carousel-dark" data-bs-ride="carousel">
	<div class="carousel-inner">
		<?php
			require_once 'connect.php';
			$sql = 'SELECT logo, name, creatorID, description FROM websites ORDER BY visits LIMIT 10;';
			$cmd = $db->prepare($sql);
			$cmd->execute();
			$sites = $cmd->fetchAll();
			foreach($sites as $site)
				echo '<div class="carousel-item"><a href="mySite.php?ID=' . $site['creatorID'] . '&pg=0&site=' . $site['name'] . '"><img src="'
						. $site['logo'] . '" alt="' . $site['name'] . '\'s logo" class="d-block logo-lg"/>' .
						'<div class="carousel-caption d-block d-md-block" style="position: static"><h5>' . $site['name'] . '</h5><p style="overflow: hidden">'
						. $site['description'] . '</p></div></a></div>';
			//echo '<li title="' . $site['name'] . '"><a href="mySite.php?ID' . $site['creatorID'] . '&pg=0&site=' . $site['name'] . '"
			// <figure><img src="' . $site['logo'] . '" alt="' . $site['name'] . '\'s logo" class="logo-lg"/><figcaption>' . $site['name'] . '</figcaption></figure></a></li>';
		?>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>
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
			$sql = 'SELECT logo, name, creatorID FROM websites WHERE name LIKE :search ORDER BY visits LIMIT 10;';
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
					echo '<li title="' . $site['name'] . '"><a href="mySite.php?ID' . $site['creatorID'] . '&pg=0&site=' . $site['name'] . '" <figure><img src="' . $site['logo'] . '" alt="' . $site['name'] . '\'s logo" class="logo-lg"/><figcaption class="text-center">' . $site['name'] . '</figcaption></figure></a></li>';
				echo '</ul>';
			}
			else
				echo '<div class="alert alert-warning"><p class="p-0 text-center">No matching results were found.</p></div>';
		}
		catch(Exception $exception)
		{
			$return = 'hosted-sites.php';
			header("location:err.php?return=$return");
			exit();
		}
	}
	require_once 'footer.php';
?>
<script defer>
	//minor script to set the first carousel item to active
	let i = document.querySelector("div#carouselExampleCaptions div.carousel-inner>div.carousel-item");
	i.classList.add("active");
	//change blank logos to a 'child' default of parent company (my site's logo)
	i = document.querySelectorAll("img");
	for(let k = 0; k < i.length; k++)
		if(i[k].getAttribute("src") === "../images/Blank.svg")
			i[k].setAttribute("src", "../images/default.svg");
</script>