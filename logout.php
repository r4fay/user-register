<?php

require("includes/config.inc.php");

$page_title = "Logout";
include("includes/header.inc.php");

if(!isset($_SESSION["first_name"]))
{
	ob_end_clean();
	header("Location: index.php");
	exit();
}
else
{
	$_SESSION = array(); //Destroy the variables
	session_destroy(); //Destroy the session itself
	setcookie(session_name(), "", time() - 3600); //Destroy the session cookie
}

echo '<p class="success">You are now logged out.</p>';

include("includes/footer.inc.php");

?>