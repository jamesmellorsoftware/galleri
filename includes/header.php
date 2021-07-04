<?php
// Page titles and outputted branding text customisable
// Go to variables.php to edit them
require_once("admin/includes/variables.php");

// Include objects and configuration
require_once("admin/includes/config.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  		<title><?php echo PAGE_TITLE; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/fontAwesome.css">
        <!-- Highway Template https://templatemo.com/tm-520-highway -->
        <link rel="stylesheet" href="css/templatemo-style.css">
        <link rel="stylesheet" href="css/useradded.css">

        <link href="https://fonts.googleapis.com/css?family=Kanit:100,200,300,400,500,600,700,800,900" rel="stylesheet">

        <?php require_once("header_scripts.php"); ?>
    </head>

    <body>