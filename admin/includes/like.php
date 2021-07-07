<?php

class Like extends db_objects {
    
    protected static $db_table = "likes";
    protected static $db_prefix = "like_";
    protected static $db_table_fields = array("like_id", "like_photo_id", "like_user_id");

    // Initialise user properties
    public $like_id;
    public $like_photo_id;
    public $like_user_id;

    public static function create_like($photo_id = "", $user_id = "") {
        // Instantiates a comment object with all properties

        if (!empty($photo_id) && !empty($user_id)) {
            $like = new Like;
            $like->like_photo_id = (int)$photo_id;
            $like->like_user_id = $user_id;

            return $like;
        }

        return false;
    }

    public static function delete_like($photo_id = "", $user_id = "") {
        if (!empty($photo_id) && !empty($user_id)) {
            global $db;

            $conditions = [Like::$db_prefix."photo_id" => $photo_id, Like::$db_prefix . "user_id" => $user_id ];

            $sql = $db->build_delete(Like::$db_table, $conditions, 1);

            $db->query($sql);

            return mysqli_affected_rows($db->connection) == 1 ? true : false;
        }

        return false;
    }

    public static function count($photo_id) {
        // Returns number of likes a photo has received

        global $db;

        $sql = $db->build_select(
            Like::$db_table,
            [['col' => '*', 'operation' => 'count']],
            [Like::$db_prefix."photo_id" => $photo_id],
            "",
            1);

        $result_set = $db->query($sql);

        $row = mysqli_fetch_assoc($result_set);

        return array_shift($row);
    }

    public static function user_liked_photo($photo_id, $user_id) {
        // Returns number of likes a photo has received

        global $db;

        $sql = $db->build_select(
            Like::$db_table,
            "*",
            [Like::$db_prefix."photo_id" => $photo_id, Like::$db_prefix."user_id" => $user_id],
            "",
            1);

        $result_set = $db->query($sql);

        $row = mysqli_fetch_assoc($result_set);

        return ($row) ? true : false;
    }

    public static function retrieve_user_likes($user_id) {
        // Returns photo IDs that a user has liked

        global $db;

        $sql = $db->build_select(
            Like::$db_table,
            [[Like::$db_prefix."photo_id"]],
            [Like::$db_prefix."user_id" => $user_id]);

        $result_set = $db->query($sql);

        $photo_ids = [];
        while ($row = mysqli_fetch_assoc($result_set)) {
            $photo_ids[] = $row['like_photo_id'];
        }

        return (!empty($photo_ids)) ? $photo_ids : false;
    }

    public static function purge_via_photo_id($photo_ids) {
        // Delete all likes where like_photo_id is equal to passed photo IDs
        // Accepts $photo_id either integer or indexed array of integers

        global $db;

        $conditions = [Like::get_table_prefix()."photo_id" => $photo_ids];

        $sql = $db->build_delete(Like::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge_via_author_id($author_ids) {
        // Delete all likes where like_user_id is equal to passed photo IDs
        // Accepts $author_ids either integer or indexed array of integers

        global $db;

        $conditions = [Like::get_table_prefix()."user_id" => $author_ids];

        $sql = $db->build_delete(Like::$db_table, $conditions);

        $db->query($sql);
    }
}

?>