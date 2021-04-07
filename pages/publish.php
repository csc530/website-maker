<?php
	require_once 'authenticate.php';
	$title = "Finalize";
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	$creatorID = $creator;
	require 'canEdit.php';
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
		echo "$siteName&creator=$creator"; ?>" method="post">
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
				$sql = 'SELECT description, COUNT(pageNumber) AS `pages` FROM websites INNER JOIN pages p on p.siteName = :siteName WHERE p.creatorID = :creator GROUP BY description,theme;';
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
			<?php
				require 'msgOrError.php'; ?>
			<label>Theme</label>
			<div class="side-by-side">
				<?php
					//set defaults so no warnings are placed in console
					$main = '000000';
					$header = '000000';
					$footer = '000000';
					//get theme from db
					require 'connect.php';
					$sql = 'SELECT theme FROM websites WHERE creatorID = :id AND name = :siteName;';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
					$cmd->bindParam(':id', $creator, PDO::PARAM_INT, 11);
					$cmd->execute();
					$theme = $cmd->fetch()['theme'];
					$db = null;
					//gets previous entry of colour variable in case one was invalid
					if(!empty($_GET['error']))
					{
						$main = '#' . $_GET['main'];
						$footer = '#' . $_GET['footer'];
						$header = '#' . $_GET['header'];
					}
					else
					{
						if(!empty($theme))
						{
							$header = $header = substr($theme, 0, 7);
							$main = $main = substr($theme, 7, 7);
							$footer = $footer = substr($theme, 14, 7);
						}
					}
					//set previous variables to correct inputs
				?>
				<label for="header">Header: </label>
				<input type="color" name="c-header" id="header" value="<?php
					echo $header ?>" />
				<label for="main">Main: </label>
				<input type="color" name="c-main" id="main" value="<?php
					echo $main ?>" />
				<label for="footer">Footer: </label>
				<input type="color" name="c-footer" id="footer" value="<?php
					echo $footer ?>" />
			</div>
		</fieldset>
		<!--todo: add an onclick to button view published site or return home-->
		<button type="submit" name="step" value="4">Finalize</button>
	</form>
<?php
	require_once 'footer.php';
?>