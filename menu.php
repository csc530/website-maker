<?php
	$title = 'Main menu';
	require_once 'header.php';
?>
	<h1>Welcome<!--Add name arg for l8r aswell as a bunch of profile stuffies too--></h1>
	<nav>
		<ul>
			<li><a href="create.php">New website</a></li>
			<li>Edit Websites</li>
			<li><a href="profile.php">profile</a></li>
		</ul>
	</nav>
	<h2>Your websites</h2>
	<ul>
		<?php
		//populate ul with website hrefs from database
		?>
	</ul>
<?php require_once 'footer.php' ?>