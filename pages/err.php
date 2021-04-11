<?php
	require 'meta.php';
?>
<h1>Uh oh!</h1>
<div class="alert bg-danger">
	<p>Sorry, It looks like something went wrong on our end. Please be patient and try again later. Thank you for your cooperation.</p>
</div>
<a href="index.php"><button type="button">Home</button></a>
<?php if(!empty($_GET['return']))
	echo '<a href="'.$_GET['return'].'"><button type="button">Return</button></a>';
require 'footer.php';
?>