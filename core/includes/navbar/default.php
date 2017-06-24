<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-nav-demo" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo Config::get('links/app_root'); ?>" class="navbar-brand hvr-grow">真 Makoto</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-nav-demo">
			<ul class="nav navbar-nav">
				<li class='dropdown hvr-sweep-to-bottom'>
					<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Dropdown <span class='caret'></span></a>
					<ul class='dropdown-menu'>
						<li class='hvr-forward'><a href='#'><i class='fa fa-link dropdown-icon' aria-hidden='true'></i> Link</a></li>
						<li class='hvr-forward'><a href='#'><i class='fa fa-link dropdown-icon' aria-hidden='true'></i> Link</a></li>
						<li role='separator' class='divider'></li>
						<li class='hvr-forward'><a href='#'><i class='fa fa-link dropdown-icon' aria-hidden='true'></i> Link</a></li>
					</ul>
				</li>
				<li><a class="hvr-sweep-to-bottom" href="#"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;Link</a></li>
				<li><a class="hvr-sweep-to-bottom" href="<?php echo Config::get('links/app_root'); ?>users.php"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;&nbsp;Members</a></li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					// If the user is logged in
					if($user->isLoggedIn()) {
						// Check to see if user has admin permissions
						if($user->hasPermission('admin')) {
							// Display Admin Backpage Link
							echo "<li><a class='hvr-sweep-to-bottom' href='" . Config::get('links/app_root') . "admin/'><i class='fa fa-wrench' aria-hidden='true'></i>&nbsp;&nbsp;Admin Page</a></li>";
						}
						// Display the rest of the links
						echo 	"<li class='dropdown hvr-sweep-to-bottom'>
									<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>{$user->data()->username} <span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li class='hvr-forward'><a href='" . Config::get('links/app_root') . "profile.php?user={$user->data()->id}'><i class='fa fa-user-circle dropdown-icon' aria-hidden='true'></i> Your Profile</a></li>
										<li class='hvr-forward'><a href='" . Config::get('links/app_root') . "settings.php'><i class='fa fa-cog dropdown-icon' aria-hidden='true'></i> Settings</a></li>
										<li role='separator' class='divider'></li>
										<li class='hvr-forward'><a href='" . Config::get('links/app_root') . "logout.php'><i class='fa fa-sign-out dropdown-icon' aria-hidden='true'></i> Logout</a></li>
									</ul>
								</li>";

					} else {
						// Ask user to sign up or login
						echo "<li><a class='hvr-sweep-to-bottom' href='" . Config::get('links/app_root') . "register.php'><i class='fa fa-user-plus' aria-hidden='true'></i>&nbsp;&nbsp;Sign Up</a></li>";
						echo "<li><a class='hvr-sweep-to-bottom' href='" . Config::get('links/app_root') . "login.php'><i class='fa fa-sign-in' aria-hidden='true'></i>&nbsp;&nbsp;Login</a></li>";
					}
				?>
			</ul>
		</div>
	</div>
</nav>