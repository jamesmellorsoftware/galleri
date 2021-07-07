<?php
require_once("../admin/includes/config.php");
require_once("../admin/includes/variables.php");

// Ajax pagination request, load next X photos
if (isset($_POST['load_more'])) {
    // Pagination limit is set at the top of this script in PHP, e.g. 25 posts
    // Offset comes from JS AJAX POST request and updates every time user loads more posts
    // Returns next photo set (in JSON?) to AJAX call to load them

    $offset = $db->connection->real_escape_string($_POST['offset']);
    $pagination_limit = $db->connection->real_escape_string($_POST['pagination_limit']);
    $source_page = $db->connection->real_escape_string($_POST['source_page']);
    $id = $db->connection->real_escape_string($_POST['id']);

    $new_set = [];

    switch ($source_page) {
        case "liked.php":
            $new_set = Photo::find_user_likes($session->user_id, $pagination_limit+1, $offset);
            foreach ($new_set as $new_photo) {
                $new_photo->like_count = Like::count($new_photo->photo_id);
                $new_photo->comment_count = Comment::count($new_photo->photo_id);
                $new_photo->filepath = $new_photo->photo_path();
                $new_photo->photo_author = User::get_name_from_id($new_photo->photo_author_id);
            }
            break;
        case "index.php":
            $new_set = Photo::find_all($pagination_limit+1, $offset);
            foreach ($new_set as $new_photo) {
                $new_photo->like_count = Like::count($new_photo->photo_id);
                $new_photo->comment_count = Comment::count($new_photo->photo_id);
                $new_photo->filepath = $new_photo->photo_path();
                $new_photo->photo_author = User::get_name_from_id($new_photo->photo_author_id);
            }
            break;
        case "photographers.php":
            $new_set = User::find_photographers($pagination_limit+1, $offset);
            foreach ($new_set as $new_photographer) $new_photographer->photographer_image = $new_photographer->get_user_image();
            break;
        case "photographergallery.php":
            $photographer_user_id = $id;
            $new_set = Photo::find_photographer_gallery($photographer_user_id, $pagination_limit+1, $offset);
            foreach ($new_set as $new_photo) {
                $new_photo->like_count = Like::count($new_photo->photo_id);
                $new_photo->comment_count = Comment::count($new_photo->photo_id);
                $new_photo->filepath = $new_photo->photo_path();
            }
            break;
    }

    echo json_encode($new_set);
}

?>