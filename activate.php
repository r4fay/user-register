<?php

require("includes/config.inc.php");

$page_title = "Activate your account";
include("includes/header.inc.php");

if  (
	isset($_GET['x'], $_GET['y']) 
	&& filter_var($_GET['x'], FILTER_VALIDATE_EMAIL)
	&& (strlen($_GET['y']) == 32 )
	)
{
	require(MYSQL);

	$email = mysqli_real_escape_string($database, $_GET["x"]);
	$code  = mysqli_real_escape_string($database, $_GET["y"]);

	//Update the database:
	$query  = "UPDATE users SET active = NULL WHERE (email = '$email' AND active = '$code') LIMIT 1";
	$result = mysqli_query($database, $query) or trigger_error("Query: $query\n<br>MySQLi Error: " . mysqli_error($database));

	if(mysqli_affected_rows($database) == 1)
	{
		echo '<p class="success">Your account is activated. You may log in now.</p>';
	}
	else
	{
		echo '<p class="error">Your account could not be activated. Shit!</p>';
	}

	mysqli_close($database);
}
else
{
	$url = BASE_URL . "index.php";
	ob_end_clean(); //Delete the buffer.
	header("Location: $url");
	exit();
}

include("includes/footer.inc.php");