<?php
	$title = 'Login';
	require_once 'header.php';
?>
    <h1>Login</h1>
    <form action="register.php" method="post">
        <fieldset>
            <label for="username">Username: </label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" required>
            <label for="confirm-password">Confirm password: </label>
            <input type="password" name="confirm-password" id="confirm-password" required>
        </fieldset>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="login.php" class="btn btn-secondary" ><button type="button">Login</button></a>
    </form>
<?php require_once 'footer.php'?>