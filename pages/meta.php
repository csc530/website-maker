<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php
			echo "$title - Web Dreamscapes"; ?></title>
	<!--todo paths: make absolute paths for below links-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<script src="../js/bootstrap.min.js" type="text/javascript" defer></script>
	<link href="../css/myStyles.css" type="text/css" rel="stylesheet" />
	<script src="../js/scripts.js" type="text/javascript" defer></script>
</head>
<body>
<header>
	<!--Bootstrap navbar https://getbootstrap.com/docs/5.0/components/navbar/-->
	<?php
		//display appropriate header for if the user is logged in or not
		$stay = true;
		require_once 'authenticate.php';
		if($loggedIn)
			require_once 'user-header.php';
		else
			require_once 'main-header.php'
	?>
</header>
<main>
	<div class="container">