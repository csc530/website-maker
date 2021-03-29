<?php
	require_once 'authenticate.php';
	$title = 'Create a website';
	$error = $_GET['error'];
	require_once 'meta.php';
?>
	<form action="website-validation.php" method="post">
		<fieldset>
			<legend>Basics</legend>
			<?php
				//display error
				if(!empty($error))
				echo "<p class='alert-danger alert'>$error</p>";
			?>
			<label for="title">Website title</label>
			<input type="text" name="title" maxlength="35" id="title" required>
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."></textarea>
		</fieldset>
		<button type="submit" name="step" id="step" value="1" class="btn btn-primary">Next</button>
	</form>
<?php require_once 'footer.php' ?>