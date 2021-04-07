<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo "$title - Web Dreamscapes"; ?></title>
	<!--todo paths: make absolute paths for below links-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="../images/cloud-computing.svg" type="image/svg+xml">
	<link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<script src="../js/bootstrap.min.js" type="text/javascript" defer></script>
	<link href="../css/myStyles.css" type="text/css" rel="stylesheet" />
	<script src="../js/scripts.js" type="text/javascript" defer></script>
</head>
<body class="bg-light">
<header class="bg-light">
	<!--Bootstrap navbar https://getbootstrap.com/docs/5.0/components/navbar/-->
	<!--Main navbar header displaying login and register links-->
	<nav class="navbar navbar-expand-lg navbar-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="index.php">Web Dreamscapes<img src="../images/cloud-computing.svg" height="45" alt="Image
			of cloud with circuits inside"></a>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
	<?php
		//display appropriate header for if the user is logged in or not
		$stay = true;
		require_once 'authenticate.php';
		if($loggedIn)
			require_once 'user-header.php';
		else
			require_once 'main-header.php';
	?>
			<img src="../images/sun.svg" alt="Picture of the sun in front of a cloud" height="50" width="50" id="theme"/>
		</div>
	</nav>
</header>
<main class="bg-light container">