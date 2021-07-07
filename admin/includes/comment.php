<?php

class Comment extends db_objects {
    protected static $db_table = "comments";
    protected static $db_prefix = "comment_";
    protected static $db_table_fields = array("comment_id", "comment_photo_id", "comment_author_id", "comment_content", "comment_date", "comment_approved");
    protected static $bulk_options = [
        ["value" => "delete",         "name"  => MODERATE_COMMENTS_BULKOPTION_DELETE],
        ["value" => "reset_likes",    "name"  => MODERATE_COMMENTS_BULKOPTION_RESET_LIKES],
        ["value" => "approve",        "name"  => MODERATE_COMMENTS_BULKOPTION_APPROVE_COMMENTS],
        ["value" => "unapprove",      "name"  => MODERATE_COMMENTS_BULKOPTION_UNAPPROVE_COMMENTS]
    ];
    protected static $search_filters = [
        "comment_id",
        "comment_photo_id",
        "comment_author",
        "comment_date_from",
        "comment_date_to",
        "comment_content",
        "comment_approved",
        "results_per_page"
    ];

    public $comment_id;
    public $comment_photo_id;
    public $comment_author_id;
    public $comment_content;
    public $comment_date;
    public $comment_approved;

    public static function create_comment($photo_id = "", $author = "", $content = "", $date="") {
        // Instantiates a comment object with all properties

        if (!empty($photo_id) && !empty($author) && !empty($content) && !empty($date)) {
            $comment = new Comment;
            $comment->comment_photo_id = (int)$photo_id;
            $comment->comment_author_id = $author;
            $comment->comment_content = $content;
            $comment->comment_date = $date;
            $comment->comment_approved = 0;

            return $comment;
        }

        return false;
    }

    public static function retrieve($photo_id = 0) {
        global $db;

        $sql = $db->build_select(
            self::$db_table,
            "*",
            ['comment_photo_id' => $photo_id],
            ["comment_photo_id" => "ASC"]);

        return self::execute_query($sql);
    }

    public static function retrieve_user_comments($user_id = 0) {
        global $db;

        $sql = $db->build_select(self::$db_table, "*", ['comment_author_id' => $user_id]);

        return self::execute_query($sql);
    }

    public function verify() {

        $comment_errors = ["content" => ""];

        $comment_errors = Comment::check_empty_inputs($this, $comment_errors);

        if (strlen($this->comment_content) > LIMIT_PHOTO_COMMENT) $comment_errors['content'] = PHOTO_COMMENT_ERROR_TOO_LONG;

        return $comment_errors;

    }

    public static function count($photo_id) {
        // Returns number of comments a photo has received

        global $db;

        $sql = $db->build_select(
            Comment::$db_table,
            [["col" => "*", "operation" => "count"]],
            [Comment::$db_prefix."photo_id" => $photo_id]
        );

        $result_set = $db->query($sql);

        $row = mysqli_fetch_assoc($result_set);

        return array_shift($row);
    }

    public static function purge_via_author_id($author_ids) {
        // Delete all comments where comment_author_id is equal to passed user IDs
        // Accepts $author_ids either integer or indexed array of integers

        $comment_ids_deleted = Comment::retrieve_user_comments($author_ids);

        Comment_Like::purge_via_comment_id($comment_ids_deleted);

        global $db;

        $conditions = [Comment::get_table_prefix()."author_id" => $author_ids];

        $sql = $db->build_delete(Comment::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge_via_photo_id($photo_ids) {
        // Delete all comments where comment_photo_id is equal to passed photo IDs
        // Accepts $photo_id either integer or indexed array of integers

        global $db;

        $conditions = [Comment::get_table_prefix()."photo_id" => $photo_ids];

        $sql = $db->build_delete(Comment::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge($comment_ids) {
        Comment::purge_comments($comment_ids);
        Comment::purge_comment_likes($comment_ids);
    }

    public static function purge_comments($comment_ids) {
        // Delete all comments where comment_id is equal to passed comment IDs
        // Accepts $comment_ids either integer or indexed array of integers

        global $db;

        $conditions = [Comment::get_table_prefix()."id" => $comment_ids];

        $sql = $db->build_delete(Comment::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge_comment_likes($comment_ids) {
        Comment_Like::purge_via_comment_id($comment_ids);
    }


    public static function get_comment_ids_for_photo($photo_ids) {
        // Retrieves all comments (IDs) that a photo has by searching its photo ID
        // Accepts $photo_id either integer or indexed array of integers

        global $db;

        $sql = $db->build_select(
            self::$db_table,
            [['col' => Comment::get_table_prefix()."id"]],
            [Comment::get_table_prefix()."photo_id" => $photo_ids]
        );

        $result_set = self::execute_query($sql);

        if (empty($result_set)) return false;

        $comment_ids = [];

        foreach ($result_set as $comment) {
            $comment_ids[] = $comment->comment_id;
        }

        return $comment_ids;
    }

    public static function approve($comment_ids) {
        global $db;

        $sql = $db->build_update(
            self::$db_table,
            [Comment::get_table_prefix()."approved" => 1],
            [Comment::get_table_prefix()."id" => $comment_ids]
        );

        $db->query($sql);
    }

    public static function unapprove($comment_ids) {
        global $db;

        $sql = $db->build_update(
            self::$db_table,
            [Comment::get_table_prefix()."approved" => 0],
            [Comment::get_table_prefix()."id" => $comment_ids]
        );

        $db->query($sql);
    }

    public static function search($search_filters, $limit = "", $offset = "") {
        // Accepts $_POST from search.php form and converts into conditions

        $conditions = [];
        $order_by = "";
        $joins = "";

        if (isset($search_filters['results_per_page']) && is_int($search_filters['results_per_page'])) {
            $limit = $search_filters['results_per_page'] + 1;
        }

        if (isset($search_filters['comment_approved'][1])) $conditions[Comment::get_table_prefix()."approved"]  = 1;
        if (isset($search_filters['comment_approved'][0])) $conditions[Comment::get_table_prefix()."approved"]  = 0;
        if (isset($search_filters['comment_content']) && !empty($search_filters['comment_content'])) {
            $conditions[Comment::get_table_prefix()."content"] = ['like' => [$search_filters['comment_content']]];
        }
        if (isset($search_filters['comment_id']) && !empty($search_filters['comment_id'])) {
            $conditions[Comment::get_table_prefix()."id"] = $search_filters['comment_id'];
        }
        if (isset($search_filters['comment_photo_id']) && !empty($search_filters['comment_photo_id'])) {
            $conditions[Comment::get_table_prefix()."photo_id"]  = $search_filters['comment_photo_id'];
        }
        if (!empty($search_filters['comment_date_from']) && empty($search_filters['comment_date_to'])) {
            $conditions[Comment::get_table_prefix()."date"] = ['>=' => [$search_filters['comment_date_from']]];
        }
        if (empty($search_filters['comment_date_from']) && !empty($search_filters['comment_date_to'])) {
            $conditions[Comment::get_table_prefix()."date"] = ['<=' => [$search_filters['comment_date_to']]];
        }
        if (!empty($search_filters['comment_date_from']) && !empty($search_filters['comment_date_to'])) {
            $conditions[Comment::get_table_prefix()."date"] = ['between' => [$search_filters['comment_date_from'], $search_filters['comment_date_to']]];
        }
        if (isset($search_filters['comment_author']) && !empty($search_filters['comment_author'])) {
            $joins = [
                ['type'       => 'full',
                 'join_table' => 'users',
                 'on'         => [Comment::get_table_prefix()."author_id" => User::get_table_prefix()."id"]]
            ];
            $conditions[User::get_table_prefix()."username"] = ['like' => [$search_filters['comment_author']]];
        }

        return Comment::find("*", $conditions, $order_by, $limit, $offset, $joins);
    }
}

?>