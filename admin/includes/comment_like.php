<?php

class Comment_Like extends db_objects {
    
    protected static $db_table = "comment_likes";
    protected static $db_prefix = "comment_like_";
    // change the below to use the $db_prefix
    protected static $db_table_fields = array("comment_like_id", "comment_like_comment_id", "comment_like_user_id");

    // Initialise user properties
    public $comment_like_id;
    public $comment_like_comment_id;
    public $comment_like_user_id;

    public static function create_like($comment_id = "", $user_id = "") {
        // Instantiates a comment like object with all properties

        if (!empty($comment_id) && !empty($user_id)) {
            $comment_like = new Comment_Like;
            $comment_like->comment_like_comment_id = (int)$comment_id;
            $comment_like->comment_like_user_id = $user_id;

            return $comment_like;
        }

        return false;
    }

    public static function delete_like($comment_id = "", $user_id = "") {
        if (!empty($comment_id) && !empty($user_id)) {
            global $db;

            $sql = "DELETE FROM " . Comment_Like::$db_table . " ";
            $sql.= "WHERE ";
            $sql.= Comment_Like::$db_prefix . "comment_id = " . $comment_id . " ";
            $sql.= "AND ";
            $sql.= Comment_Like::$db_prefix . "user_id = " . $user_id . " ";

            $db->query($sql);

            return mysqli_affected_rows($db->connection) == 1 ? true : false;
        }

        return false;
    }

    public static function count($comment_id) {
        // Returns number of likes a comment has received

        global $db;

        $sql = "SELECT COUNT(*) FROM " . Comment_Like::$db_table . " ";
        $sql.= "WHERE ";
        $sql.= Comment_Like::$db_prefix . "comment_id = '{$comment_id}' ";

        $result_set = $db->query($sql);

        $row = mysqli_fetch_assoc($result_set);

        return array_shift($row);
    }

    public static function user_liked_photo($comment_id, $user_id) {
        // Returns number of likes a photo has received

        global $db;

        $sql = "SELECT * FROM " . Comment_Like::$db_table . " ";
        $sql.= "WHERE ";
        $sql.= Comment_Like::$db_prefix . "comment_id = " . $comment_id . " ";
        $sql.= "AND ";
        $sql.= Comment_Like::$db_prefix . "user_id = " . $user_id . " ";
        $sql.= "LIMIT 1";

        $result_set = $db->query($sql);

        $row = mysqli_fetch_assoc($result_set);

        return ($row) ? true : false;
    }

    public static function purge_via_photo_id($photo_ids) {
        // Retrieve all comment IDs related to a photo via its photo ID
        // Delete all comment likes with those comment IDs
        // Accepts $photo_id either integer or indexed array of integers

        $comment_ids = Comment::get_comment_ids_for_photo($photo_ids);

        Comment_Like::purge_via_comment_id($comment_ids);
    }

    public static function purge_via_comment_id($comment_ids) {
        // Retrieve all comment IDs related to a comment via its comment ID
        // Delete all comment likes with those comment IDs
        // Accepts $comment_ids either integer or indexed array of integers

        global $db;

        $conditions = [Comment_Like::get_table_prefix()."comment_id" => $comment_ids];

        $sql = $db->build_delete(Comment_Like::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge_via_author_id($author_ids) {
        // Delete all comment likes made by a user
        // Accepts $author_ids either integer or indexed array of integers

        global $db;

        $conditions = [Comment_Like::get_table_prefix()."user_id" => $author_ids];

        $sql = $db->build_delete(Comment_Like::$db_table, $conditions);

        $db->query($sql);
    }
}

?>