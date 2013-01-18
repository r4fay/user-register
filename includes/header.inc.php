<?php

ob_start(); //start output buffering

session_start(); //initialize session

if(!isset($page_title))
{
	$page_title = "User Registration";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title; ?></title>
	<link rel="stylesheet" href="includes/style.css">
</head>
<body>

	<div id="container">

		<div id="header">
			<h1>My Awesome Site</h1>
		</div>

		<div id="content">
		<!-- End of header.inc.php -->