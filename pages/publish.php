<?php
	
	require_once 'authenticate.php';
	$title = "Finalize";
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	require_once 'meta.php';
?>
<form action="website-validation.php?siteTitle=<?php
	echo "$siteName"; ?>" method="post">
	<h1>Review</h1>
	<h2>Website Overview</h2>
	<!--todo add mini view of pages with link to edit them and special link for splash back to website edit not page-->
	<h3>Website name: <?php
			echo $siteName; ?></h3>
	<h3>Website overview:</h3>
	<?php
		require_once 'connect.php';
		//get description opf website and total number of pages
		$sql = 'SELECT description, COUNT(pageNumber) AS `pages` FROM websites INNER JOIN pages p on p.siteName = :siteName WHERE p.creatorID = :creator GROUP BY description;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$pageInfo = $cmd->fetch();
		//display website's description
		echo '<p>' . $pageInfo['description'] . '</p>';
		//display total number of pages
		echo '<h3>Page count: ' . $pageInfo['pages'] . '</h3>';
	?>
	<!--
<label>Colour theme
	Add option to personalize website theme
</label>
-->
	<!--todo: add an onclick to button view published site or return home-->
	<button type="submit" name="step" value="4">Finalize</button>
</form>