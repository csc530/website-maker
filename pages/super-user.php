<?php
	require_once 'authenticate.php';
	$title = 'Super user';
	require_once 'meta.php';
	//code to update user's email
	if(!empty($_POST['newEmail']))
	{
		require 'connect.php';
		$sql='UPDATE creators SET email = :newEmail WHERE email = :email;';
		$cmd=$db->prepare($sql);
		$cmd->bindParam(':email', $_POST['editUser'], PDO::PARAM_STR, 128);
		$cmd->bindParam(':newEmail', $_POST['newEmail'], PDO::PARAM_STR, 128);
		$cmd->execute();
		$db=null;
	}
?>
	<h1 class="h1">Web Dreamscapes administrative panel</h1>
	<?php require_once 'msgOrError.php';?>
	<form action="register.php" method="post">
		<h2>Add user: </h2>
		<label for="email" >Email: </label>
		<input type="email" name="email" id="email" maxlength="128" required />
		<label for="password">Password: </label>
		<input type="password" name="password" maxlength="128" id="password" required />
		<input type="hidden" name="confirm-password" />
		<!--empty hidden element for register page to redirect back here instead of login-->
		<input type="hidden" name="superuser" value="true" />
		<button type="submit" class="btn-outline-primary">Submit</button>
	</form>
	<form action="super-user.php" method="post">
		<h2>Edit user: </h2>
<!--		Used to push label for new email to the next line-->
		<div>
		<label for="editUser">Change user email: </label>
		<select class="form-control-sm" name="editUser" id="editUser" required>
			<?php
				require 'connect.php';
				$alph = 'ABCDEFGHILKMNOPQRSTUVWXYZ';
				for($i = 0; $i < 26; $i++)
				{
					
					$letter = $alph[$i];
					echo "<optgroup label='$letter'>";
					//selects email from registered based if the first character mathces position in alpahbet in looop
					$sql = 'SELECT email AS email FROM creators WHERE SUBSTRING(email,1,1) = :letter';
					
					try
					{
						$cmd = $db->prepare($sql);
						$cmd->bindParam(':letter', $letter, PDO::PARAM_STR, 1);
						$cmd->execute();
						$users = $cmd->fetchAll();
					}
					catch(Exception $ex){
					}
					foreach($users as $user)
						if(!empty($user['email']))
						echo '<option>'.$user['email'].'</option>';
						echo '</optgroup>';
				}
				$db=null;
			?>
		</select>
		</div>
		<label for="newEmail">New email: </label>
		<input type="email" name="newEmail" id="newEmail"/>
		<button type="submit" class="btn-outline-danger">Change</button>
	</form>
	<h2>Delete users: </h2>
	<ul class="side-by-side">
		<?php
			require 'connect.php';
			$sql='SELECT email FROM creators';
			$cmd = $db->prepare($sql);
			$cmd->execute();
			$users = $cmd->fetchAll();
			foreach($users as $user)
				if(!empty($user['email']))
					echo '<li class="my-1"><a class="no-link" href="delete.php?creator='.$user['email'].'"  onclick="return confirmDelete()" >'
							.$user['email']
							.'<button type="button" class="btn mx-1 btn-outline-danger btn-sm">-</button></a></li>';
		?>
	</ul>
	<a href="menu.php"><button class="btn-success btn-lg">Return</button></a>
<?php
	require_once 'footer.php';
?>