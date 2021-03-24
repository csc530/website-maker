<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo "$title"; ?></title>
	<!--todo paths: make absolute paths for below links-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
	<link href="../css/customstyles.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<!--Bootstrap navbar https://getbootstrap.com/docs/5.0/components/navbar/-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="../pages/index.php">Site name</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
               <li class="nav-item">
                    <a class="nav-link" href="../pages/signup.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/login.php" role="button" >Login</a>
                </li>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<div class="container">