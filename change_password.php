<?php

require("includes/config.inc.php");

$page_title = "Change your password";
include("includes/header.inc.php");

if(!isset($_SESSION["user_id"]))
{
	$url = BASE_URL . "index.php";
	ob_end_clean(); //Delete the buffer
	header("Location: {$url}");
	exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	require(MYSQL);
	$password = false;

	//Check for a new password and match against the confirmed password:
	if(preg_match("/^[a-z0-9_-]{4,30}$/", $_POST["password_1"]))
	{
		if($_POST["password_1"] == $_POST["password_2"])
		{
			$password = mysqli_real_escape_string($database, $_POST["password_1"]);
		}
		else
		{
			echo '<p class="error">Your password did not match the confirmed password.</p>';
		}
	}
	else
	{
		echo '<p class="error">Please enter a valid password.</p>';
	}

	if($password)
	{
		$query = "UPDATE users SET password = SHA1('$password') WHERE user_id = {$_SESSION['user_id']} LIMIT 1";
		$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($database));

		if(mysqli_affected_rows($database) == 1)
		{
			echo '<p class="success">Your password has been changed.</p>';
			mysqli_close($database);
			include("includes/footer.inc.php");
			exit();
		}
		else
		{
			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current one.</p>';
		}
	}
	else //failed the validation test
	{
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($database);
}

?>

<h2>Change your password</h2>
<form action="change_password.php" method="post">
	<ul>
		<li>
			<label for="password_1">New password:</label>
			<input type="text" id="password_1" name="password_1" value="<?php if(isset($_POST['password_1'])) echo $_POST['password_1']; ?>" size="20" maxlength="60">
		</li>
		<li>
			<label for="password_2">Confirm new password:</label>
			<input type="text" id="password_2" name="password_2" value="<?php if(isset($_POST['password_2'])) echo $_POST['password_1']; ?>" size="20" maxlength="60">
		</li>
		<li>
			<input type="submit" name="submit" value="Reset">
		</li>
	</ul>
</form>

<?php include("includes/footer.inc.php"); ?>