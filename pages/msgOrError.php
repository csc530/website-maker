<?php
	$error = $_GET['error'];
	$msg = $_GET['msg'];
	if(!empty($error))
		echo "<div class='alert alert-warning'><p class='p-0'>$error</p></div>";
	else if(!empty($msg))
		echo "<div class='alert alert-info'><p class='p-0'>$msg</p></div>";
?>