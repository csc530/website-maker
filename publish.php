<?php
	$redirect = true;
	require_once 'authenticate.php';
	$title = "Finalize";
	$sitename = $_GET['siteTitle'];
	require_once 'meta.php';
	?>
<form action="website-validation.php?siteTitle=<?php echo "$sitename"; ?>" method="post">
		<h1>Review</h1>
	<h2>Website Overview</h2>
	<!--todo add mini view of pages with link to edit them and special link for splash back to website edit not page-->
<label>Colour theme
	<!--Add option to personalize website theme-->
</label>
	<!--todo: add an onclick to button view published site or return home-->
	<button type="submit" name="step" value="4">Finalize</button>
</form>