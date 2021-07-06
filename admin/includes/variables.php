<?php

require_once("config.php");

// ===== ADMIN ===== //
// GENERAL ADMIN
define("ADMIN_PAGINATION_LOAD_MORE", "Load More");
define("BULK_OPTIONS_SELECT_OPTION", "Select Option");
define("SELECT_DESELECT_ALL", "Select / Deselect All");
define("SITE_TITLE", "Galleri | Admin Dashboard");
define("ERROR_EMPTY_FIELD", "Please fill in all fields marked with *.");
define("ERROR_EMPTY_IMAGE", "Please select an image.");

// Add user
define("ADD_USER_BUTTON", "Add User");
define("ADD_USER_LABEL", "User image");
define("ADD_USER_PLACEHOLDER_EMAIL", "Email");
define("ADD_USER_PLACEHOLDER_FIRSTNAME", "First Name");
define("ADD_USER_PLACEHOLDER_LASTNAME", "Last Name");
define("ADD_USER_PLACEHOLDER_PASSWORD", "Password");
define("ADD_USER_PLACEHOLDER_USERNAME", "Username");
define("ADD_USER_ROLE_ADMIN", "Admin");
define("ADD_USER_ROLE_PHOTOGRAPHER", "Photographer");
define("ADD_USER_ROLE_SELECT", "Select user role");
define("ADD_USER_ROLE_USER", "User");
define("ADD_USER_SUCCESS", "User added successfully.");
define("ADD_USER_TITLE", "Add User");

// Edit photo
define("EDIT_PHOTO_BUTTON", "Submit Changes");
define("EDIT_PHOTO_HEADER", "Edit Photo:");
define("EDIT_PHOTO_PLACEHOLDER_POST", "Post");
define("EDIT_PHOTO_PLACEHOLDER_SUBTITLE", "Subtitle");
define("EDIT_PHOTO_PLACEHOLDER_TITLE", "Title");
define("EDIT_PHOTO_SUCCESS", "Photo edited successfully.");

// Edit user
define("EDIT_USER_BUTTON", "Edit User");
define("EDIT_USER_HEADER", "Edit User:");
define("EDIT_USER_PLACEHOLDER_EMAIL", "Email");
define("EDIT_USER_PLACEHOLDER_FIRSTNAME", "First Name");
define("EDIT_USER_PLACEHOLDER_LASTNAME", "Last Name");
define("EDIT_USER_PLACEHOLDER_PASSWORD", "Password");
define("EDIT_USER_PLACEHOLDER_USERNAME", "Username");
define("EDIT_USER_ROLE_ADMIN", "Admin");
define("EDIT_USER_ROLE_PHOTOGRAPHER", "Photographer");
define("EDIT_USER_ROLE_SELECT", "Select User Role");
define("EDIT_USER_ROLE_USER", "User");
define("EDIT_USER_SUCCESS", "User edited successfully.");

// Left sidebar
define("SIDEBAR_ADD", "Add");
define("SIDEBAR_ADD_PHOTO", "Photo");
define("SIDEBAR_ADD_USER", "User");
define("SIDEBAR_MODERATE", "Moderate");
define("SIDEBAR_MODERATE_COMMENTS", "Comments");
define("SIDEBAR_MODERATE_PHOTOS", "Photos");
define("SIDEBAR_MODERATE_USERS", "Users");

// Moderate comments
define("MODERATE_COMMENTS_APPROVED", "Approved");
define("MODERATE_COMMENTS_BULKOPTION_APPROVE_COMMENTS", "Approve comments");
define("MODERATE_COMMENTS_BULKOPTION_DELETE", "Delete");
define("MODERATE_COMMENTS_BULKOPTION_RESET_LIKES", "Reset likes");
define("MODERATE_COMMENTS_BULKOPTION_UNAPPROVE_COMMENTS", "Unapprove comments");
define("MODERATE_COMMENTS_BY", "by");
define("MODERATE_COMMENTS_COMMENT", "Comment");
define("MODERATE_COMMENTS_HEADER", "Moderate: Comments");
define("MODERATE_COMMENTS_NO_RESULTS", "No comments. Clear search.");
define("MODERATE_COMMENTS_ON", "on");
define("MODERATE_COMMENTS_ON_PHOTO", "on photo");
define("MODERATE_COMMENTS_TEXT_LIMIT", 500);
define("MODERATE_COMMENTS_UNAPPROVED", "Unapproved");

// Moderate photos
define("MODERATE_PHOTOS_BULKOPTION_DELETE", "Delete");
define("MODERATE_PHOTOS_BULKOPTION_PURGE_COMMENTS", "Purge comments");
define("MODERATE_PHOTOS_BULKOPTION_RESET_LIKES", "Reset likes");
define("MODERATE_PHOTOS_BY", "by");
define("MODERATE_PHOTOS_EDIT", "Edit");
define("MODERATE_PHOTOS_HEADER", "Moderate: Photos");
define("MODERATE_PHOTOS_NO_RESULTS", "No comments. Clear search.");
define("MODERATE_PHOTOS_ON", "on");
define("MODERATE_PHOTOS_PHOTO", "Photo");
define("MODERATE_PHOTOS_TEXT_LIMIT", 500);
define("MODERATE_PHOTOS_VIEW", "View");

// Moderate users
define("MODERATE_USERS_BANNED", "BANNED");
define("MODERATE_USERS_BULKOPTION_BAN", "Ban");
define("MODERATE_USERS_BULKOPTION_CHANGE_ADMIN", "Change to Admin");
define("MODERATE_USERS_BULKOPTION_CHANGE_PHOTOGRAPHER", "Change to Photographer");
define("MODERATE_USERS_BULKOPTION_CHANGE_USER", "Change to User");
define("MODERATE_USERS_BULKOPTION_DELETE", "Delete");
define("MODERATE_USERS_BULKOPTION_UNBAN", "Unban");
define("MODERATE_USERS_EDIT", "Edit User");
define("MODERATE_USERS_HEADER", "Moderate: Users");
define("MODERATE_USERS_NO_RESULTS", "No comments. Clear search.");

// Search
define("CLEAR_SEARCH", "Clear");
define("RESULTS_PER_PAGE_DEFAULT", "Results per Page");
define("SEARCH_SEARCH", "Search");

// Search comments
define("SEARCH_COMMENTS_APPROVED", "Approved");
define("SEARCH_COMMENTS_PLACEHOLDER_AUTHOR", "Author");
define("SEARCH_COMMENTS_PLACEHOLDER_CONTENT", "Content contains");
define("SEARCH_COMMENTS_PLACEHOLDER_DATE_FROM", "From dd/mm/yyyy");
define("SEARCH_COMMENTS_PLACEHOLDER_DATE_TO", "To dd/mm/yyyy");
define("SEARCH_COMMENTS_PLACEHOLDER_ID", "ID");
define("SEARCH_COMMENTS_PLACEHOLDER_PHOTO_ID", "Photo ID");
define("SEARCH_COMMENTS_UNAPPROVED", "Unapproved");

// Search photos
define("SEARCH_PHOTOS_PLACEHOLDER_AUTHOR", "Posted by (username)");
define("SEARCH_PHOTOS_PLACEHOLDER_ID", "Photo ID");
define("SEARCH_PHOTOS_PLACEHOLDER_CONTENT", "Content contains");
define("SEARCH_PHOTOS_PLACEHOLDER_DATE_FROM", "From dd/mm/yyyy");
define("SEARCH_PHOTOS_PLACEHOLDER_DATE_TO", "To dd/mm/yyyy");
define("SEARCH_PHOTOS_PLACEHOLDER_SUBTITLE", "Subtitle");
define("SEARCH_PHOTOS_PLACEHOLDER_TITLE", "Title");

// Search users
define("SEARCH_USERS_ADMIN", "Admin");
define("SEARCH_USERS_BANNED", "Banned");
define("SEARCH_USERS_HAS_PHOTO", "Has photo?");
define("SEARCH_USERS_PHOTOGRAPHER", "Photographer");
define("SEARCH_USERS_PLACEHOLDER_EMAIL", "Email");
define("SEARCH_USERS_PLACEHOLDER_FIRSTNAME", "First Name");
define("SEARCH_USERS_PLACEHOLDER_ID", "User ID");
define("SEARCH_USERS_PLACEHOLDER_LASTNAME", "Last Name");
define("SEARCH_USERS_PLACEHOLDER_USERNAME", "Username");
define("SEARCH_USERS_USER", "User");

// Upload
define("UPLOAD_HEADER", "Upload to your Gallery");
define("UPLOAD_FILE_LABEL", "Upload file");
define("UPLOAD_PLACEHOLDER_POST", "Post");
define("UPLOAD_PLACEHOLDER_SUBTITLE", "Subtitle");
define("UPLOAD_PLACEHOLDER_TITLE", "Title");
define("UPLOAD_SUCCESS", "Post added to gallery.");
define("UPLOAD_UPLOAD", "Upload to Galleri");


// ===== GALLERI ===== //
// Head
define("PAGE_TITLE", "Galleri");
define("TOPLEFT_TITLE", "Galleri");

// Footer
define("FOOTER_TEXT", "Galleri 2021");

// Header
define("HEADER_BELOW_SUBTITLE", "Click the arrow to begin.");
define("HEADER_SUBTITLE", "The virtual gallery.");
define("HEADER_TITLE", "Welcome to <em>Galleri</em>.");

// Index
define("INDEX_NO_PHOTOS", "No photos yet.");

// Liked photos
define("LIKED_HEADER", "My Liked Photos");
define("LIKED_NO_PHOTOS", "No photos yet. Like some!");

// Login
define("LOGIN_ALREADY_HAVE_ACCOUNT", "Don't have an account? Register here.");
define("LOGIN_ERROR_PASSWORD_INCORRECT", "Incorrect password");
define("LOGIN_ERROR_SYMBOLS_PASSWORD", "Password can only contain numbers, letters, and symbols . _ - &");
define("LOGIN_ERROR_SYMBOLS_USERNAME", "Username can only contain numbers, letters, and symbols . _ -");
define("LOGIN_ERROR_USERNAME_NOT_FOUND", "That username doesn't exist.");
define("LOGIN_ERROR_PASSWORD_TOO_LONG", "Password must not exceed " . LIMIT_PASSWORD . " characters.");
define("LOGIN_ERROR_USERNAME_TOO_LONG", "Username must not exceed " . LIMIT_USERNAME . " characters.");
define("LOGIN_PLACEHOLDER_PASSWORD", "Password");
define("LOGIN_PLACEHOLDER_USERNAME", "Username");
define("LOGIN_SUBMIT_BUTTON", "Log in");
define("LOGIN_SUCCESS_1", "Login successful.<br />");
define("LOGIN_SUCCESS_2", "Logged in as ");
define("LOGIN_SUCCESS_3", "<a href='index.php'>Go to Galleri</a><br />");

// Main menu
define("MENU_ADMIN", "Admin");
define("MENU_BOTTOM", "Select your option");
define("MENU_HOME", "Home");
define("MENU_LIKED", "My Liked Photos");
define("MENU_LOGIN", "Login");
define("MENU_LOGOUT", "Logout");
define("MENU_PHOTOGRAPHERS", "Photographers");
define("MENU_REGISTER", "Register");

// Pagination
define("PAGINATION_LOAD_MORE", "Load More");

// Photo
define("PHOTO_BY", "by");
define("PHOTO_COMMENT_BUTTON", "Create Comment");
define("PHOTO_COMMENT_LOGIN", "Log in to comment.");
define("PHOTO_COMMENT_PLACEHOLDER", "Leave a comment on this post");
define("PHOTO_COMMENT_SUCCESS", "Comment added.");

// Photographer gallery
define("PHOTOGRAPHER_NO_PHOTOS", "This photographer has not uploaded any photos yet.");

// Photographers
define("PHOTOGRAPHERS_NO_PHOTOGRAPHERS", "No photographers yet.");
define("PHOTOGRAPHERS_TITLE", "Photographers");

// Register
define("REGISTRATION_ALREADY_HAVE_ACCOUNT", "Already have an account? Log in here.");
define("REGISTRATION_BACK_TO_INDEX", "Back to Galleri");
define("REGISTRATION_ERROR_EMAIL_INVALID", "Please enter a valid email address");
define("REGISTRATION_ERROR_EMAIL_TAKEN", "Email address already in use");
define("REGISTRATION_ERROR_EMAIL_TOO_LONG", "Email address must not exceed " . LIMIT_EMAIL . " characters.");
define("REGISTRATION_ERROR_FIRSTNAME_TOO_LONG", "First name must not exceed " . LIMIT_FIRSTNAME . " characters.");
define("REGISTRATION_ERROR_LASTNAME_TOO_LONG", "Last name must not exceed " . LIMIT_LASTNAME . " characters.");
define("REGISTRATION_ERROR_PASSWORD_TOO_LONG", "Password must not exceed " . LIMIT_PASSWORD . " characters.");
define("REGISTRATION_ERROR_ROLE_INVALID", "User role invalid");
define("REGISTRATION_ERROR_SYMBOLS_EMAIL", "Please enter a valid email address");
define("REGISTRATION_ERROR_SYMBOLS_FIRSTNAME", "First name can only contain numbers and letters");
define("REGISTRATION_ERROR_SYMBOLS_LASTNAME", "Last name can only contain numbers and letters");
define("REGISTRATION_ERROR_SYMBOLS_PASSWORD", "Password can only contain numbers, letters, and symbols . _ - &");
define("REGISTRATION_ERROR_SYMBOLS_USERNAME", "Username can only contain numbers, letters, and symbols . _ -");
define("REGISTRATION_ERROR_USERNAME_TAKEN", "Username already in use");
define("REGISTRATION_ERROR_USERNAME_TOO_LONG", "Username must not exceed " . LIMIT_USERNAME . " characters.");
define("REGISTRATION_LABEL_USERIMAGE", "Upload your profile picture");
define("REGISTRATION_PLACEHOLDER_EMAIL", "youremail@emailprovider.com");
define("REGISTRATION_PLACEHOLDER_FIRSTNAME", "First Name");
define("REGISTRATION_PLACEHOLDER_LASTNAME", "Last Name");
define("REGISTRATION_PLACEHOLDER_PASSWORD", "Password");
define("REGISTRATION_PLACEHOLDER_USERNAME", "Username");
define("REGISTRATION_REGISTER", "Register");
define("REGISTRATION_SUCCESS_MESSAGE",
    "Registration successful.<br />
    Your account is awaiting approval.<br />
    You will receive an email when your account has been processed.");
?>