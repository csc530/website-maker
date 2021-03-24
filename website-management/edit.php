<?php
	//TODO: validate session or redirect
	//toDo validate: validate user has access to edit this page if they somehow got onto the wrong website's edit page somehow?
	$title = 'Create a website';
	require_once '../header.php';
	$websiteID = $_GET['websiteID'];
	require_once '../connect.php';
	//get name and description of website from db using websiteID
	$sql = 'SELECT name, description FROM websites WHERE ID=:id;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':id', $websiteID, PDO::PARAM_INT);
	$cmd->execute();
	$db = null;
	$websiteInfo = $cmd->fetch();
?>
	<h1><?php echo $websiteInfo['name'];?></h1>
	<form action="edit-validation.php?websiteID=<?echo "$websiteID";?>" method="post">
		<fieldset>
			<legend>Access</legend>
			<label for="user">Add user</label>
			<input type="text" name="user" maxlength="128" id="user" required />
			<ul>
				<?php
					require_once '../connect.php';
					$websiteID = $_GET['websiteID'];
					//query all current website editors for selected website
					$sql = 'SELECT email FROM creators INNER JOIN websites_admin wa on :email = wa.admin WHERE websiteID = :websiteID';
					$cmd = $db->prepare($sql);
					//todo email: get email from session
					$cmd->bindParam(':email', $email, PDO::PARAM_STR, 128);
					$cmd->bindParam(':websiteID', $websiteID, PDO::PARAM_INT);
					$cmd->execute();
					$users = $cmd->fetchAll();
					foreach($users as $user)
						///use get method to assign which delete button is clicked
						echo '<li><a href="edit-validation.php?delete='.$user['email'].'" ><button class="btn btn-dark" id="'.$user['email'].'"  type="button"> - </button></a>' . $user['email'] . '</li>';
				?>
			</ul>
			<button type="submit" name="add" value="true" class="btn-primary">Add</button>
		</fieldset>
	</form>
	<form action="edit-validation.php?websiteID=<?echo "$websiteID";?>" method="post">
		<fieldset>
			<legend>Basics</legend>
			<label for="title">Website title</label>
			<!-- insert current name and description of website to form-->
			<input type="text" name="title" maxlength="35" id="title" required
			       value="<?php echo $websiteInfo['name']; ?>">
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."><?php echo $websiteInfo['description']; ?></textarea>
		</fieldset>
		<button type="submit" name="update" value="true" class="btn-primary">Update</button>
	</form>
	<form action="edit-validation.php?websiteID=<?echo "$websiteID";?>" method="post">
	<button type="submit" name="edit" value="true" class="btn btn-secondary">Edit content</button>
	</form>
<?php require_once 'footer.php' ?>