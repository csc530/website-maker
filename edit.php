<?php
	require_once 'authenticate.php';
	$title = 'Create a website';
	require_once 'meta.php';
	$siteName = $_GET['siteTitle'];
	$creator = $_GET['creator'];
	//if no creator GET argument has been passed it means they are the creator of the website
	if(empty($creator))
		$creator = $_SESSION['email'];
	require_once 'connect.php';
	//validate user has access to edit this page, if they are an admin/creator (if they somehow got onto the wrong website's edit page somehow?)
	$sql = 'SELECT admin FROM (SELECT * FROM websites_admin WHERE siteName = :siteName) AS selectedSite WHERE admin = :email OR creator = :email';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
	$cmd->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR, 128);
	$cmd->execute();
	$permitted = $cmd->fetch();
	//if the query is empty it means they are not permitted to edit, then redirect to home
	if(empty($permitted))
	{
		$db=null;
		header('location:menu.php?error=You are not permitted to view that site.');
		exit();
	}
	//get name and description of website from db (with creator and siteName PK)
	$sql = 'SELECT name, description FROM websites WHERE creator = :creator AND name = :siteName;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
	$cmd->bindParam(':siteName',$siteName, PDO::PARAM_STR, 35);
	$cmd->execute();
	$db = null;
	$websiteInfo = $cmd->fetch();
?>
	<!--Sectioned off the edits in separate forms for ease of submission (allowed to have required (attr) for appropriate inputs rather than no
	required attributes on any input and needing additional checks server-side) and handling in validation page-->
	<h1><?php echo $websiteInfo['name'];?></h1>
	<form action="edit-validation.php?siteTitle=<?php echo "$siteName&creator=$creator";?>" method="post">
		<fieldset>
			<legend>Basics</legend>
			<label for="title">Website title</label>
			<!-- insert current name and description of website to form-->
			<input type="text" name="title" maxlength="35" id="title" required
			       value="<?php echo $websiteInfo['name']; ?>" />
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."><?php echo $websiteInfo['description']; ?></textarea>
		</fieldset>
		<button type="submit" name="update" value="true" class="btn-primary">Update</button>
	</form>
	<form action="edit-validation.php?siteTitle=<?php echo "$siteName&creator=$creator";?>" method="post">
		<fieldset>
			<legend>Access</legend>
			<label for="user">Add user</label>
			<input type="text" name="user" maxlength="128" id="user" required >
			<ul>
				<?php
					require 'connect.php';
					//query all current website editors for selected website excluding the creator, because you can't remove the creator of the
					// website as an admin
					$sql = 'SELECT admin FROM websites_admin WHERE siteName = :siteName AND admin != :creator AND creator = :creator';
					$cmd = $db->prepare($sql);
					$cmd->bindParam(':creator', $creator, PDO::PARAM_STR, 128);
					$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
					$cmd->execute();
					$users = $cmd->fetchAll();
					//loop through query displaying each user with a corresponding delete button
					foreach($users as $user)
						echo '<li><a href="edit-validation.php?delete='.$user['admin']."&creator=$creator&siteTitle=$siteName".'" ><button
						class="btn btn-dark" id="'.$user['admin'].'"
						type="button"> - </button></a>' . $user['admin'] . '</li>';
				?>
			</ul>
			<button type="submit" name="add" value="true" class="btn-primary">Add</button>
		</fieldset>
	</form>
	<form action="edit-webpages.php?pageNumber=1&siteTitle=<?php echo "$siteName&creator=$creator";?>" method="post">
	<button type="submit" name="edit" value="true" class="btn btn-secondary">Edit content</button>
	</form>
<?php require_once 'footer.php' ?>