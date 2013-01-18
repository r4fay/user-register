		<!-- Start of footer.inc.php -->
		</div> <!-- end content -->

		<div id="menu">
			<ul>
				<li><a href="index.php">Home</a></li>

				<?php

					if(isset($_SESSION["user_id"]))
					{
						echo '<li><a href="logout.php">Logout</a></li>';
						echo '<li><a href="change_password.php">Change Password</a></li>';

						if($_SESSION["user_level"] == 1)
						{
							echo '<li><a href="view_users.php">View Users</a></li>';
							echo '<li><a href="#">Control Panel</a></li>';
						}
					} 
					else 
					{
						echo '<li><a href="register.php">Register</a></li>';
						echo '<li><a href="login.php">Login</a></li>';
						echo '<li><a href="forgot_password.php">Retrieve Password</a></li>';
					}

				?>

				<li><a href="#">Some Page</a></li>
				<li><a href="#">Another Page</a></li>
			</ul>
		</div> <!-- end menu -->

	</div> <!-- end container -->
</body>
</html>

<?php ob_end_flush(); ?>