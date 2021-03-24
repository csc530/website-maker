<?php
	require_once 'authenticate.php';
	$title = 'Create a website';
	require_once '../header.php';
?>
	<form action="website-validation.php" method="post">
		<fieldset>
			<legend>Basics</legend>
			<label for="title">Website title</label>
			<input type="text" name="title" maxlength="35" id="title" required>
			<label for="pages" title="This can be changed later">Amount of pages</label>
			<!--tentative max number of pages final TBD-->
			<input name="pages" id="pages" type="number" min="1" max="10" required value="1" title="This can be changed later" />
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."></textarea>
		</fieldset>
		<button type="submit" name="step" id="step" value="1" class="btn btn-primary">Next</button>
	</form>
<?php require_once 'footer.php' ?>