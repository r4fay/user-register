<?php

require("includes/config.inc.php");

$page_title = "Login";
include("includes/header.inc.php");

if(isset($_SESSION["user_id"]))
{
	header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	require(MYSQL);
	$errors = array();

	//Validate the email address:
	if(!empty($_POST["email"]))
	{
		$email = mysqli_real_escape_string($database, $_POST["email"]);
	}
	else
	{
		$errors[] = "Please enter your email address.";
	}

	//Validate the password:
	if(!empty($_POST["password"]))
	{
		$password = mysqli_real_escape_string($database, $_POST["password"]);
	}
	else
	{
		$errors[] = "Please enter your password.";
	}

	// $query  = "SELECT active FROM users WHERE (email = '$email' AND password = SHA1('$password'))";
	// $result = mysqli_query($database, $query);

	// $shitnigga = mysqli_fetch_array($result, MYSQLI_ASSOC);
	// print_r($shitnigga);

	if(empty($errors))
	{
		$query  = "SELECT user_id, first_name, user_level FROM users 
				   WHERE (email = '$email' AND password = SHA1('$password')) 
				   AND active IS NULL";
		$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($database));

		if(@mysqli_num_rows($result) == 1) //A match was made
		{
			//Register the values:
			$_SESSION = mysqli_fetch_array($result, MYSQLI_ASSOC);
			mysqli_free_result($result);
			mysqli_close($database);

			//Redirect the user:
			$url = BASE_URL . "index.php";
			ob_end_clean(); //Delete the buffer.
			header("Location: $url");
			exit();
		}
		else
		{
			echo '<p class="error">Either the email address and password entered do not match or your account is not activated.</p>';
		}
	}
	else
	{
		echo '<ul class="error">';
		echo "<h3>Error(s) occured!</h3>";
		foreach($errors as $error)
		{
			echo "<li>{$error}</li>";
		}
		echo "</ul>";
	}

	mysqli_close($database);
}

?>

<h2>Login</h2>
<p class="notice">Your browser must allow cookies in order to log in.</p>

<form action="login.php" method="post">
	<ul>
		<li>
			<label for="email">Email address:</label>
			<input type="text" id="email" name="email" value="<?php if(isset($trimmed['email'])) echo $trimmed['first_name']; ?>" size="20" maxlength="60">
		</li>
		<li>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" value="<?php if(isset($trimmed['password'])) echo $trimmed['password_2']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<input type="submit" name="submit" value="Login">
		</li>
	</ul>
</form>

<?php include("includes/footer.inc.php"); ?>