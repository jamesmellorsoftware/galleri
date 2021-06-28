<?php
include "includes/head.php";

if (!isset($_GET['action']) || empty($_GET['action'])) header("Location: index.php");

$action = $_GET['action'];

require_once("subpages/" . $action . ".php");

include "search.php";

include "includes/footer.php";
?>