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

// Define pagination limits
define("PAGINATION_LIMIT_INDEX", 5);
define("PAGINATION_LIMIT_LIKED_PHOTOS", 5);
define("PAGINATION_LIMIT_MODERATE_COMMENTS", 5);
define("PAGINATION_LIMIT_MODERATE_PHOTOS", 5);
define("PAGINATION_LIMIT_MODERATE_USERS", 5);
define("PAGINATION_LIMIT_PHOTOGRAPHER_GALLERY", 5);
define("PAGINATION_LIMIT_PHOTOGRAPHERS", 5);

// Define photo limits
define("LIMIT_PHOTO_COMMENT", 2000);
define("LIMIT_PHOTO_POST", 10000);
define("LIMIT_PHOTO_SUBTITLE", 300);
define("LIMIT_PHOTO_TITLE", 300);

// Define user character limits
define("LIMIT_EMAIL", 30);
define("LIMIT_FIRSTNAME", 30);
define("LIMIT_LASTNAME", 30);
define("LIMIT_PASSWORD", 30);
define("LIMIT_USERNAME", 30);

// Define user accepted patterns
define("REGEX_EMAIL", "/^[a-zA-Z0-9\.\@\_\-]/");
define("REGEX_FIRSTNAME", "/^[a-zA-Z0-9]/");
define("REGEX_LASTNAME", "/^[a-zA-Z0-9]/");
define("REGEX_PASSWORD", "/^[a-zA-Z0-9\.\_\-\&]/");
define("REGEX_USERNAME", "/^[a-zA-Z0-9\.\_\-]/");

?>