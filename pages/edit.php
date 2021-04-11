<?php
	require_once 'authenticate.php';
	$title = 'Create a website';
	require_once 'meta.php';
	$siteName = $_GET['siteTitle'];
	$creatorID = $_GET['creator'];
	//if no creator GET argument has been passed it means they are the creator of the website
	if(empty($creatorID))
		$creatorID = $_SESSION['id'];
	require_once 'connect.php';
	//validate user has access to edit this page, if they are an admin/creator (if they somehow got onto the wrong website's edit page somehow?)
	require_once 'canEdit.php';
	//get name and description of website from db (with creator and siteName PK)
	$sql = 'SELECT name, description FROM websites WHERE creatorID = :creator AND name = :siteName;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':creator', $creatorID, PDO::PARAM_INT, 11);
	$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
	$cmd->execute();
	$db = null;
	$websiteInfo = $cmd->fetch();
?>
	<!--Sectioned off the edits in separate forms for ease of submission (allowed to have required (attr) for appropriate inputs rather than no
	required attributes on any input and needing additional checks server-side) and handling in validation page-->
	<h1><?php
			echo $websiteInfo['name']; ?></h1>
<?php
	require_once 'msgOrError.php';
?>
	<div class="side-by-side">
		<form action="edit-validation.php?siteTitle=<?php
			echo "$siteName&creator=$creatorID"; ?>" method="post">
			<fieldset>
				<legend>Basics</legend>
				<label for="title">Website title</label>
				<!-- insert current name and description of website to form-->
				<input type="text" name="title" maxlength="35" id="title" required
				       value="<?php
					       echo $websiteInfo['name']; ?>" />
				<label for="description">Description</label>
				<textarea name="description" id="description" required
				          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."><?php
						echo $websiteInfo['description']; ?></textarea>
				<button type="submit" name="update" value="true" class="btn-primary">Update</button>
			</fieldset>
		</form>
		<form action="edit-validation.php?siteTitle=<?php
			echo "$siteName&creator=$creatorID"; ?>" method="post">
			<fieldset>
				<legend>Access</legend>
				<label for="user">Add user</label>
				<div class="input-group input-group-lg" style="flex-wrap: nowrap">
					<input type="text" name="user" maxlength="128" id="user" required>
					<button type="submit" name="add" value="true" class="btn-primary">Add</button>
				</div>
				<ul>
					<?php
						require 'connect.php';
						//query all current website editors for selected website excluding the creator, because you can't remove the creator of the
						// website as an admin
						$sql = 'SELECT admin FROM websites_admin WHERE siteName = :siteName AND admin != (SELECT email FROM creators WHERE ID = :creator) AND creator = :creator';
						$cmd = $db->prepare($sql);
						$cmd->bindParam(':creator', $creatorID, PDO::PARAM_INT, 11);
						$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
						$cmd->execute();
						$users = $cmd->fetchAll();
						//loop through query displaying each user with a corresponding delete button
						foreach($users as $user)
							echo '<li><a href="edit-validation.php?delete=' . $user['admin'] . "&creator=$creatorID&siteTitle=$siteName" . '" ><button
						class="btn btn-dark" id="' . $user['admin'] . '"
						type="button"> - </button></a>' . $user['admin'] . '</li>';
					?>
				</ul>
			</fieldset>
		</form>
		<form action="edit-validation.php?siteTitle=<?php
			echo "$siteName&creator=$creatorID"; ?>" method="post">
			<fieldset>
				<legend>Colour theme</legend>
				<?php
					$creator = $creatorID;
					require 'siteThemeForm.php';
				?>
				<button type="submit" class="btn-primary">Change</button>
			</fieldset>
		</form>
	</div>
	<form action="edit-webpages.php?pageNumber=1&siteTitle=<?php
		echo "$siteName&creator=$creatorID"; ?>" method="post">
		<button type="submit" name="edit" value="true" class="btn-warning">Edit pages</button>
		<a href="menu.php" onclick="return confirm('Any changes made to website will not be saved. Do you wish to continue?')">
			<button type="button"
			        class="btn-danger">Exit
			</button>
		</a>
	</form>
<?php
	require_once 'footer.php' ?>