<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php">Site name</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="profile.php"><?php
							echo $_SESSION['email']; ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="menu.php" role="button">My sites</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php" role="button">Logout</a>
				</li>
				<li class="nav-item">
					<a class="nav-link alert-danger" href="super-user.php" role="button" title="Edit and delete all accounts on web">Super user</a>
				</li>
			</ul>
			<form class="d-flex">
				<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success" type="submit">Search</button>
			</form>
		</div>
	</div>
</nav>