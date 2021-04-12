<?php
	require_once 'authenticate.php';
	$title = "Finalize";
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	$creatorID = $creator;
	require 'canEdit.php';
	require_once 'meta.php';
	try
	{
		//get logo of website if any
		require_once 'connect.php';
		$sql = 'SELECT logo FROM websites WHERE creatorID = :creator AND name = :siteName;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$path = $cmd->fetch()['logo'];
		//get description opf website and total number of pages
		$sql = 'SELECT description, COUNT(pageNumber) AS `pages` FROM websites INNER JOIN pages p on p.siteName = :siteName WHERE p.creatorID = :creator GROUP BY description,theme;';
		$cmd = $db->prepare($sql);
		$cmd->bindParam(':creator', $creator, PDO::PARAM_INT, 11);
		$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
		$cmd->execute();
		$db = null;
		$pageInfo = $cmd->fetch();
	}
	catch(Exception $ex)
	{
		$db = null;
		header('location:err.php');
	}
	if(empty($path))
		$alt = "$siteName's logo.";

?>
	<h1>Finalization</h1>
	<form action="website-validation.php?siteTitle=<?php
		echo "$siteName&creator=$creator"; ?>" method="post">
		<fieldset>
			<legend class="display-6">Overview</legend>
			<h3>Website name: <?php
					echo "<strong>$siteName</strong>"; ?></h3>
			<h3>Logo: <img class="logo-lg" src="<?php
					echo $path; ?>" alt="<?php
					echo $alt; ?>"></h3>
			<h3>Description:</h3>
			<?php
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
					require 'siteThemeForm.php';
				?>
			</div>
		</fieldset>
		<a target="_blank" href="mySite.php?ID=<?php echo "$creatorID&site=$siteName&pg=0" ?>"><button type="button" class="btn-secondary">Preview</button></a>
		<button type="submit" name="step" value="4" class="btn-primary">Finalize</button>
	</form>
<?php
	require_once 'footer.php';
?>