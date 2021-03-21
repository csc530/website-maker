<?php
	$title = 'Login';
	require_once 'header.php';
?>
    <h1>Login</h1>
    <form action="register.php" method="post">
        <fieldset>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" required>
        </fieldset>
        <button type="submit" class="btn btn-primary">login</button>
        <a href="signup.php" class="btn btn-secondary" ><button type="button">Sign Up</button></a>
    </form>
<?php require_once 'footer.php'?>