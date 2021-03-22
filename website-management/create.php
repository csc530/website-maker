<?php
	//TODO: validate session or redirect
	$title = 'Create a website';
	require_once '../header.php';
?>
	<form action="website-validation.php" method="post">
		<fieldset>
			<legend>Basics</legend>
			<label for="title">Website title</label>
			<input type="text" name="title" maxlength="35" id="title" required>
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."></textarea>
		</fieldset>
		<button type="submit" name="step" id="step" value="1" class="btn btn-primary">Next</button>
	</form>
<?php require_once 'footer.php' ?>