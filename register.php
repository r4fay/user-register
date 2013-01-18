<?php

require("includes/config.inc.php");

$page_title = "Register";
include("includes/header.inc.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	require(MYSQL); //Connect to database.
	$trimmed = array_map("trim", $_POST); //Trim all incoming data.
	$errors = array();

	//$first_name = $last_name = $email = $password = false; //Assume invalid values.

	//Check for a first name:
	if(preg_match("/^[a-zA-Z0-9_-]{3,20}$/i", $trimmed["first_name"]))
	{
		$first_name = mysqli_real_escape_string($database, $trimmed["first_name"]);
	}
	else
	{
		$errors[] = 'Please enter your first name.';
	}

	//Check for a last name:
	if(preg_match("/^[a-zA-Z0-9_-]{3,20}$/i", $trimmed["last_name"]))
	{
		$last_name = mysqli_real_escape_string($database, $trimmed["last_name"]);
	}
	else
	{
		$errors[] = 'Please enter your last name.';
	}

	//Check for an email address:
	if(filter_var($trimmed["email"], FILTER_VALIDATE_EMAIL))
	{
		$email = mysqli_real_escape_string($database, $trimmed["email"]);
	}
	else
	{
		$errors[] = 'Please input a valid email address.';
	}

	//Check for a password and match againast the confirmed password:
	if(preg_match("/^[a-z0-9_-]{4,30}$/", $trimmed["password_1"]))
	{
		if($trimmed["password_1"] == $trimmed["password_2"])
		{
			$password = mysqli_real_escape_string($database, $trimmed["password_1"]);
		}
		else
		{
			$errors[] = 'Your password did not match the confirmed password.';
		}
	}
	else
	{
		$errors[] = 'Please enter a valid password.';
	}

	//If everything's okay...
	if(empty($errors))
	{
		$query  = "SELECT user_id FROM users WHERE email = '$email'";
		$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQLi Error: " . mysqli_error($database));

		if(mysqli_num_rows($result) == 0)
		{
			//Create the activation code:
			$activation_code = md5(uniqid(rand(), true));

			//Add the user to the database.
			$query  = "INSERT INTO users (email, password, first_name, last_name, active, registration_date) 
					   VALUES('$email', SHA1('$password'), '$first_name', '$last_name', '$activation_code', NOW())";
			$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQLi Error: " . mysqli_error($database));

			if(mysqli_affected_rows($database) == 1)
			{
				//Send the email:
				$body = "Thank you for registering. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . "activate.php?x=" . urlencode($email) . "&y=$activation_code";
				mail($trimmed["email"], "Registration Confirmation", $body, "From: admin@sitename.com");

				//Finish the page:
				echo "<h3>Thank you for registering!</h3>";
				echo "<p>A confirmation email has been send to your address. Please click on the link in order to activate your account.</p>";
				include("includes/footer.inc.php");
				exit();
			}
			else
			{
				echo '<p class="error">You could not be registered due to a system error. We apologize for the incovenience.</p>';
			}
		}
		else
		{
			echo '<p class="error">The email address provided is already registered. If you have forgetten your password, click <a href="retrieve_password.php">here</a> to have your password sent to you.</p>';
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

<h2>Register</h2>

<form action="register.php" method="post">
	<ul>
		<li>
			<label for="first_name">First name:</label>
			<input type="text" id="first_name" name="first_name" value="<?php if(isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<label for="last_name">Last name:</label>
			<input type="text" id="last_name" name="last_name" value="<?php if(isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<label for="email">Email address:</label>
			<input type="text" id="email" name="email" value="<?php if(isset($trimmed['email'])) echo $trimmed['email']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<label for="password_1">Password:</label>
			<input type="password" id="password_1" name="password_1" value="<?php if(isset($trimmed['password_1'])) echo $trimmed['password_1']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<label for="password_2">Confirm password:</label>
			<input type="password" id="password_2" name="password_2" value="<?php if(isset($trimmed['password_2'])) echo $trimmed['password_2']; ?>" size="20" maxlength="30">
		</li>
		<li>
			<input type="submit" name="submit" value="Register">
		</li>
	</ul>
</form>

<?php include("includes/footer.inc.php"); ?>