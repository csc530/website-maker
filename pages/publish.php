<?php
	
	require_once 'authenticate.php';
	$title = "Finalize";
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	require_once 'meta.php';
	//get logo of website if any
	require_once 'connect.php';
	$sql = 'SELECT logo FROM websites WHERE creatorID = :creator AND name = :siteName;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
	$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
	$cmd->execute();
	$path = $cmd->fetch()['logo'];
	if(empty($path))
		$alt = "$siteName's logo.";
?>
	<h1>Finalization</h1>
	<form action="website-validation.php?siteTitle=<?php
		echo "$siteName?creator=$creator"; ?>" method="post">
		<fieldset>
			<legend class="display-6">Overview</legend>
			<!--todo add mini view of pages with link to edit them and special link for splash back to website edit not page-->
			<h3>Website name: <?php
					echo "<strong>$siteName</strong>"; ?></h3>
			<h3>Logo: <img class="logo-lg" src="<?php
					echo $path; ?>" alt="<?php
					echo $alt; ?>"></h3>
			<h3>Description:</h3>
			<?php
				require 'connect.php';
				//get description opf website and total number of pages
				$sql = 'SELECT description, COUNT(pageNumber) AS `pages` FROM websites INNER JOIN pages p on p.siteName = :siteName WHERE p.creatorID = :creator GROUP BY description;';
				$cmd = $db->prepare($sql);
				$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
				$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
				$cmd->execute();
				$db = null;
				$pageInfo = $cmd->fetch();
				//display website's description
				echo '<p>' . $pageInfo['description'] . '</p>';
				//display total number of pages
				echo '<h3>Page count: ' . $pageInfo['pages'] . '</h3>';
			?>
		</fieldset>
		<fieldset>
			<legend class="display-6">Personalization</legend>
			<label>Theme</label>
			<div class="side-by-side">
				<label for="header">Header: </label>
				<input type="color" name="c-sec" id="header" />
				<label for="main">Main: </label>
				<input type="color" name="c-main" id="main" />
				<label for="footer">Footer: </label>
				<input type="color" name="c-ter" id="footer" />
			</div>
		</fieldset>
		<!--todo: add an onclick to button view published site or return home-->
		<button type="submit" name="step" value="4">Finalize</button>
	</form>
<?php
	require_once 'footer.php';
?>