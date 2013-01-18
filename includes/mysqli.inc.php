<?php

DEFINE ("DB_USER", "root");
DEFINE ("DB_PASS", "root");
DEFINE ("DB_HOST", "localhost");
DEFINE ("DB_NAME", "user_register");

$database = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(!$database)
{
	trigger_error("Sorry, something went terribly wrong.");
	echo "<pre>" . mysqli_connect_error() . "</pre>";
}
else
{
	mysqli_set_charset($database, "UTF8");
}
