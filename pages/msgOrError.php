<?php
	$error = $_GET['error'];
	$msg = $_GET['msg'];
	if(!empty($error))
		echo "<div class='alert alert-warning'><p>$error</p></div>";
	else if(!empty($msg))
		echo "<div class='alert alert-info'><p>$msg</p></div>";
?>