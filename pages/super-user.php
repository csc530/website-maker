<?php
	require_once 'authenticate.php';
	$title = 'Super user';
	require_once 'meta.php';
?>
	<h1>Registered users of Web Dreamscapes: </h1>
	<form action="register.php" method="post">
		<h2>Add user: </h2>
		<label for="email">Email: </label>
		<input type="email" name="email" id="email" maxlength="128" required />
		<label for="password">Password: </label>
		<input type="password" name="password" maxlength="128" id="password" required />
		<input type="hidden" name="confirm-password" />
		<!--empty hidden element for register page to redirect back here instead of login-->
		<input type="hidden" name="superuser" value="true" />
		<button type="submit">Submit</button>
	</form>
<?php

?>
	</form>
<?php
	require_once 'footer.php';
?>