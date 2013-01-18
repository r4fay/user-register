<?php

# SETTINGS #
define("LIVE", FALSE); //Flag variable for site status.
define("EMAIL", ""); //Administrator email address.
define("BASE_URL", "http://localhost/register_system/"); //Site URL (base for all redirections).
define("MYSQL", "includes/mysqli.inc.php"); //Location of MySQLi connection script.
date_default_timezone_get("Asia/Tashkent"); //Default timezone for website.
# SETTINGS #

# ERROR MANAGEMENT SYSTEM #
function rError($e_number, $e_message, $e_file, $e_line, $e_vars)
{
	//Build the error message:
	$message = "An error has occured in script {$e_file} on line {$e_line}: $e_message\n";

	//Add the date and time:
	$message .= "Date/time: " . date("n-j-Y H:i:s") . "\n";

	if(!LIVE)
	{
		//Show the error message;
		echo '<div class="error">';
			echo nl2br($message);
			echo '<pre>';
				print_r($e_vars, 1) . '\n';
				debug_print_backtrace();
			echo '</pre>';
		echo '</div>';
	}
	else
	{
		//Send an email to the admin:
		$body = $message. "\n" . print_r($e_vars, 1);
		// mail(EMAIL, "Site Error!", $body, "From: email@example.com");

		if($e_number != E_NOTICE)
		{
			echo '<div class="error">';
				echo 'A system error occured. We apologize for the inconvenience.';
			echo '</div>';
		}
	}
}
# ERROR MANAGEMENT SYSTEM #

set_error_handler("rError");