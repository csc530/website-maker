<?php
	$title = 'Login';
	require_once '../page-layouts/meta.php';
	$email = "";
	$email = $_GET['email'];
?>
	<form action="register.php" method="post">
		<h1>Register</h1>
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
		<button type="submit" class="btn btn-primary" >Register</button>
		<a href="login.php" >
			<button type="button" class="btn btn-secondary">Login</button>
		</a>
	</form>
<?php require_once '../page-layouts/footer.php' ?>