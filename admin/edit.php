<?php
include "includes/head.php";

$action = $_GET['action'];

require_once("subpages/" . $action . ".php");

include "includes/footer.php";
?>