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
			</ul>
			<a class="d-flex nav-item nav-link alert-danger" href="super-user.php" role="button" title="Edit and delete all accounts on web">Super user</a>