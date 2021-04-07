<?php
	require_once 'authenticate.php';
	$title = 'Create a website';
	require_once 'meta.php';
?>
	<form action="website-validation.php" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Basics</legend>
			<?php
				require_once 'msgOrError.php';
			?>
			<label for="title">Website title</label>
			<input type="text" name="title" maxlength="35" id="title" required>
			<label for="description">Description</label>
			<textarea name="description" id="description" required
			          placeholder="Give a brief welcome and overview to your clients/users/visitors about your website."></textarea>
		</fieldset>
		<fieldset>
			<legend>Logo</legend>
			<label for="logo">Image: </label>
			<input id="logo" name="logo" type="file" accept=".png,.jpg,.jpeg,.svg,.gif" />
		</fieldset>
		<button type="submit" name="step" id="step" value="1" class="btn-primary">Next</button>
	</form>
<?php
	require_once 'footer.php' ?>