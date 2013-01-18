<?php

require("includes/config.inc.php");

$page_title = "Retrieve your password";
include("includes/header.inc.php");

require(MYSQL);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$user_id = false; //assume nothing

	if(!empty($_POST["email"]))
	{
		//Check for the existence of that email address:
		$query  = "SELECT user_id FROM users WHERE email = '" . mysqli_real_escape_string($database, $_POST["email"]) . "'";
		$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($database));

		if(mysqli_num_rows($result) == 1)
		{
			list($user_id) = mysqli_fetch_array($result, MYSQLI_NUM);
		}
		else
		{
			echo '<p class="error">The submitted email address does not match those on file.</p>';
		}
	}
	else
	{
		echo '<p class="error">Please enter your email address.</p>';
	}

	if($user_id)
	{
		//Generate a new, random password:
		$password = substr(md5(uniqid(rand(), true)), 3, 10);

		//Update the database:
		$query = "UPDATE users SET password = SHA1('$password') WHERE user_id = $user_id LIMIT 1";
		$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($database));

		if(mysqli_affected_rows($database) == 1)
		{
			$body = "Your password has been reset. Your new password is {$password}.";
			mail($_POST["email"], "Your new password", $body, "From: admin@somesite.com");

			echo '<p class="success">Your password has been changed. You will receive your new password at your email address.</p>';
			echo $body;

			mysqli_close($database);
			include("includes/footer.inc.php");
			exit();
		}
		else
		{
			echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>';
		}
	}
	else
	{
		echo '<p class="error">Please try again.</p>';
		mysqli_close($database);
	}
}

?>

<h2>Reset your password</h2>
<p>Enter your email address below and your password will be reset.</p>

<form action="forgot_password.php" method="post">
	<ul>
		<li>
			<label for="email">Email address:</label>
			<input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" size="20" maxlength="60">
		</li>
		<li>
			<input type="submit" name="submit" value="Reset">
		</li>
	</ul>
</form>

<?php include("includes/footer.inc.php"); ?>