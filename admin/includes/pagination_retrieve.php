<?php
require_once("config.php");
require_once("variables.php");

// Ajax pagination request, load next X photos
if (isset($_POST['load_more'])) {
    // Pagination limit is set at the top of this script in PHP, e.g. 25 posts
    // Offset comes from JS AJAX POST request and updates every time user loads more posts
    // Returns next photo set (in JSON?) to AJAX call to load them

    $offset = $_POST['offset'];
    $pagination_limit = $_POST['pagination_limit'];
    $source_page = $_POST['source_page'];
    $action = $_POST['action'];
    $id = $_POST['id'];
    $search_filters = ($_POST['search_filters'] == "") ? "" : json_decode($_POST['search_filters'], true);

    $new_set = [];

    switch ($source_page) {
        case "moderate.php":
            switch ($action) {
                case "moderate_photos":
                    if ($session->user_is_admin()) {
                        $new_set = (is_array($search_filters)) ? Photo::search($search_filters, $pagination_limit+1, $offset) : Photo::find_all($pagination_limit+1, $offset);
                    }
                    if ($session->user_is_photographer()) {
                        $new_set = (is_array($search_filters)) ? Photo::search_photographer($session->user_id, $search_filters, $pagination_limit+1, $offset) : Photo::find_photographer_gallery($session->user_id, $pagination_limit+1, $offset);
                    }
                    foreach ($new_set as $new_photo) {
                        $new_photo->photo_like_count = Like::count($new_photo->photo_id);
                        $new_photo->photo_comment_count = Comment::count($new_photo->photo_id);
                        $new_photo->photo_filepath = $new_photo->photo_path();
                        $new_photo->photo_author = User::get_name_from_id($new_photo->photo_author_id);
                        $new_photo->photo_date = date('jS M Y', strtotime($new_photo->photo_date));
                    }
                    break;
                case "moderate_comments":
                    $new_set = (is_array($search_filters)) ? Comment::search($search_filters, $pagination_limit+1, $offset) : Comment::find_all($pagination_limit+1, $offset);
                    foreach ($new_set as $new_comment) {
                        $new_comment->comment_author = User::get_name_from_id($new_comment->comment_author_id);
                        $new_comment->comment_date = date('jS M Y', strtotime($new_comment->comment_date));
                        $new_comment->comment_like_count = Comment_Like::count($new_comment->comment_id);
                    }
                    break;
                case "moderate_users":
                    $new_set = (is_array($search_filters)) ? User::search($search_filters, $pagination_limit+1, $offset) : User::find_all($pagination_limit+1, $offset);
                    foreach ($new_set as $new_user) {
                        $new_user->user_profile_image = $new_user->get_user_image();
                    }
                    break;
            }
            break;
    }

    echo json_encode($new_set);
}

?>