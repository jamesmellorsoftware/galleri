<?php
// Define relative paths and directory separator
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined("SITE_ROOT") ? null : define("SITE_ROOT", "C:" . DS . "xampp" . DS . "htdocs" . DS . "galleri");
defined("ADMIN_ROOT") ? null : define("ADMIN_ROOT", SITE_ROOT . DS . "admin");
defined("INCLUDES_PATH") ? null : define("INCLUDES_PATH", SITE_ROOT . DS . "includes");

// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'galleri');

// require_onces for all created classes
require_once('db.php');
require_once('session.php');
require_once('db_objects.php');
require_once('user.php');
require_once('photo.php');
require_once('like.php');
require_once('comment_like.php');
require_once('comment.php');
?>