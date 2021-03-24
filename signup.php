<?php
	$title = 'Login';
	require_once 'header.php';
	$email = "";
	$email = $_GET['email'];
?>
	<h1>Register</h1>
	<form action="register.php" method="post">
		<fieldset>
			<label for="email">Email: </label>
			<input type="email" name="email" id="email" required <?php echo "value='$email'";?> />
			<label for="password">Password: </label>
			<input type="password" name="password" id="password" required>
			<label for="confirm-password">Confirm password: </label>
			<input type="password" name="confirm-password" id="confirm-password" required />
			<?php
				if(!empty($_GET['error']))
					echo '<p class="p-0 alert-danger">' . $_GET['error'] . '</p>'
			?>
		</fieldset>
		<button type="submit" class="btn btn-primary" >Register</button>
		<a href="login.php" >
			<button type="button" class="btn btn-secondary">Login</button>
		</a>
	</form>
<?php require_once 'footer.php' ?>