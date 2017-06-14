<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-nav-demo" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo Config::get('links/app_root'); ?>" class="navbar-brand">OOP Login System</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-nav-demo">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
					</ul>
				</li>
				<li><a href="#">Link</a></li>
				<li><a href="#">Link</a></li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					// If the user is logged in
					if($user->isLoggedIn()) {
						// Check to see if user has admin permissions
						if($user->hasPermission('admin')) {
							// Display Admin Backpage Link
							echo "<li><a href='" . Config::get('links/app_root') . "admin/'>Admin</a></li>";
						}
						// Display the rest of the links
						echo 	"<li class='dropdown'>
									<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Profile <span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='" . Config::get('links/app_root') . "update.php'>Change Name</a></li>
										<li><a href='" . Config::get('links/app_root') . "changepassword.php'>Change Password</a></li>
										<li><a href='" . Config::get('links/app_root') . "profile.php?user={$user->data()->username}'>Your Profile</a></li>
										<li><a href='" . Config::get('links/app_root') . "settings.php?user={$user->data()->username}'>Settings</a></li>
										<li role='separator' class='divider'></li>
										<li><a href='" . Config::get('links/app_root') . "logout.php'>Logout</a></li>
									</ul>
								</li>";

					} else {
						// Ask user to sign up or login
						echo "<li><a href='" . Config::get('links/app_root') . "register.php'>Sign Up</a></li>";
						echo "<li><a href='" . Config::get('links/app_root') . "login.php'>Login</a></li>";
					}
				?>
			</ul>
		</div>
	</div>
</nav>