<?php
require_once("admin/includes/head.php");
$session->logout();
header("Location: login.php");
?>