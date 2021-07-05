<?php
ob_start();
// Include objects and configuration
require_once("variables.php");
require_once("config.php");
if (!$session->is_signed_in()) header("Location: ../index.php");
if (!$session->admin_access()) header("Location: ../index.php");
?>

<!doctype html>
<html lang="en">

<head>
	<title><?php echo SITE_TITLE; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/linearicons/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- JQUERY -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
	
	<div id="wrapper">
		<?php
			include "includes/nav.php";
			include "includes/left_sidebar.php";
		?>
		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">