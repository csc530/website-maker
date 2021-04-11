<?php
	//File to add inputs and labels for site's theme to a form auto populating from database or error header
	//set defaults so no warnings are placed in console
	$main = '000000';
	$header = '000000';
	$footer = '000000';
	//get theme from db
	//no try catch as if there is an error it will fall back on to default colour theme
	require 'connect.php';
	$sql = 'SELECT theme FROM websites WHERE creatorID = :id AND name = :siteName;';
	$cmd = $db->prepare($sql);
	$cmd->bindParam(':siteName', $siteName, PDO::PARAM_STR, 35);
	$cmd->bindParam(':id', $creator, PDO::PARAM_INT, 11);
	$cmd->execute();
	$theme = $cmd->fetch()['theme'];
	$db = null;
	//gets previous entry of colour variable in case one was invalid
	if(!empty($_GET['error']))
	{
		$main = '#' . $_GET['main'];
		$footer = '#' . $_GET['footer'];
		$header = '#' . $_GET['header'];
	}
	else
	{
		if(!empty($theme))
		{
			$header = $header = substr($theme, 0, 7);
			$main = $main = substr($theme, 7, 7);
			$footer = $footer = substr($theme, 14, 7);
		}
	}
	//set previous variables to correct inputs
?>
<label for="header">Header: </label>
<input type="color" name="c-header" id="header" value="<?php echo $header ?>" />
<label for="main">Main: </label>
<input type="color" name="c-main" id="main" value="<?php echo $main ?>" />
<label for="footer">Footer: </label>
<input type="color" name="c-footer" id="footer" value="<?php echo $footer ?>" />