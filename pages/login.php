<?php
	$title = 'Login';
	require_once 'meta.php';
?>
	<form action="register.php" method="post">
		<h1>Login</h1>
		<label for="email">Email: </label>
		<input type="email" name="email" id="email" required />
		<label for="password">Password: </label>
		<input type="password" name="password" id="password" required />
		<?php
			require_once 'msgOrError.php';
		?>
		<button type="submit" class="btn-primary" name="login" value="true">login</button>
		<a href="signup.php">
			<button class="btn-secondary" type="button">Sign Up</button>
		</a>
	</form>
<?php
	require_once 'footer.php' ?>