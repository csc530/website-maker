<?php
	$title = 'Login';
	require_once 'header.php';
?>
	<h1>Login</h1>
	<form action="../register.php" method="post">
		<fieldset>
			<label for="email">Email: </label>
			<input type="email" name="email" id="email" required />
			<label for="password">Password: </label>
			<input type="password" name="password" id="password" required />
			<?php
				if(!empty($_GET['error']))
					echo '<p class="p-0 alert-danger">' . $_GET['error'] . '</p>'
			?>
		</fieldset>
		<button type="submit" class="btn btn-primary" name="login" value="true">login</button>
		<a href="signup.php">
			<button  class="btn btn-secondary"type="button">Sign Up</button>
		</a>
	</form>
<?php require_once 'footer.php' ?>