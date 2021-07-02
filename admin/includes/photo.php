<?php

class Photo extends db_objects {
    
    protected static $db_table = "photos";
    protected static $db_prefix = "photo_";
    protected static $db_table_fields = array("photo_id", "photo_author_id", "photo_title", "photo_subtitle", "photo_text", "photo_date", "photo_filename");
    protected static $bulk_options = [
        ["value" => "delete",         "name"  => "Delete"],
        ["value" => "reset_likes",    "name"  => "Reset Likes"],
        ["value" => "purge_comments", "name"  => "Purge Comments"]
    ];
    protected static $search_filters = [
        "photo_id",
        "photo_author",
        "photo_title",
        "photo_subtitle",
        "photo_text",
        "photo_date_from",
        "photo_date_to",
        "results_per_page"
    ];

    // Initialise user properties
    public $photo_id;
    public $photo_author_id;
    public $photo_title;
    public $photo_subtitle;
    public $photo_text;
    public $photo_date;
    public $photo_filename;
    public $photo_filetype;
    public $photo_filesize;

    public $tmp_path;
    public $upload_dir = "img/photos";

    public function set_file($file) {
        // $_FILES['uploaded_file'] is the accepted argument
        // Returns errors if no file uploaded or error thrown
        // Sets photo object instance properties based off $_FILES if present

        if (empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "No file uploaded.";
            return false;
        }
        
        if ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        }

        $this->photo_filename = basename($file['name']);
        $this->tmp_path = $file['tmp_name'];
        $this->photo_filetype = $file['type'];
        $this->photo_filesize = $file['size'];

        return true;
    }

    public function save(){
        if ($this->photo_id) return $this->update_photo();

        if (!empty($this->errors)) return false;

        if (empty($this->photo_filename) || empty($this->tmp_path)) {
            $this->errors[] = "File not available.";
            return false;
        }

        $target_path = ".." . DS . $this->upload_dir . DS . $this->photo_filename;

        if (file_exists($target_path)) {
            $this->errors[] = "File {$this->photo_filename} already exists.";
            return false;
        }

        if (move_uploaded_file($this->tmp_path, $target_path)) {
            if ($this->create()) {
                unset($this->tmp_path);
                return true;
            }
        } else {
            $this->errors[] = "Error moving file. Folder probably lacks permissions.";
            return false;
        }
    }

    public function update_photo(){
        // Update ONE record of corresponding class in database (condition: ID)
        // NOTE: class MUST be instantiated and properties set first before calling!

        if (!$this) return false;

        global $db;

        // Retrieve class properties and place into array to build SQL statement
        $properties_to_set = $this->set_properties();

        // Set WHERE condition to id = $this->id so only 1 record affected
        $conditions = [Photo::get_table_prefix() . "id" => $this->photo_id];

        if (!$sql = $db->build_update(Photo::get_table_name(), $properties_to_set, $conditions, 1)) return false;

        return $db->query($sql);
    }

    public function delete_photo(){
        if ($this->delete()) {
            $target_path = ADMIN_ROOT . DS . $this->photo_path();
        }
    }

    public static function purge($photo_ids) {
        // Deletes the photo and all of its comments, comment likes, and likes
        // i.e. all trace of photo removed from database entirely
        // NOTE: must initialise an instance of photo object to use this!
        Photo::purge_comments($photo_ids);
        Photo::purge_likes($photo_ids);
        Photo::purge_photos($photo_ids);
    }

    public static function purge_photos($photo_ids) {
        // Delete all photos where photo_id is equal to passed photo IDs
        // Accepts $photo_id either integer or indexed array of integers

        global $db;

        $conditions = [Photo::get_table_prefix()."id" => $photo_ids];

        $photos = Photo::find_all("", "", "", $conditions);
    
        foreach ($photos as $photo) {
            $photo_filename = $photo->photo_filename;
            if (file_exists("../img/photos/" . $photo_filename)) unlink("../img/photos/" . $photo_filename);
        }

        $sql = $db->build_delete(Photo::$db_table, $conditions);

        $db->query($sql);
    }

    public static function purge_comments($photo_ids){
        // Deletes all of a photo's comments, comment likes, and likes
        // i.e. all trace of photo removed from database entirely
        // Accepts $photo_ids either integer or array of photo IDs
        Comment::purge_via_photo_id($photo_ids);
        Comment_Like::purge_via_photo_id($photo_ids);
    }

    public static function purge_likes($photo_ids){
        // Deletes the photo and all of its comments, comment likes, and likes
        // i.e. all trace of photo removed from database entirely
        // Accepts $photo_ids either integer or array of photo IDs
        Like::purge_via_photo_id($photo_ids);
    }

    public function photo_path(){
        return $this->upload_dir.DS.$this->photo_filename;
    }

    public static function find_photographer_gallery($photographer_user_id, $pagination_limit = "", $offset="") {
        return Photo::find_by_column("author_id", $photographer_user_id, $pagination_limit, $offset);
    }

    public static function find_user_likes($user_id, $limit = "", $offset = "") {
        $conditions = [Like::get_table_prefix()."user_id" => $user_id];
        $joins = [['join_table' => Like::get_table_name(), 'on' => [Photo::$db_prefix.'id' => Like::get_table_prefix().'photo_id']]];
        return Photo::find_all($limit, $offset, $joins, $conditions);
    }

    public static function verify_upload($photo_values) {
        // Accepts object instance as argument
        // Checks:
        // - fields empty?

        $photo_values = [
            "photo_title" => $photo_values->photo_title,
            "photo_subtitle" => $photo_values->photo_subtitle,
            "photo_text" => $photo_values->photo_text
        ];

        $upload_errors = ["title"  => "", "subtitle"  => "", "text" => ""];

        $upload_errors = User::check_empty_form($photo_values);

        return $upload_errors;
    }

    public static function search_photographer($photographer_id, $search_filters, $limit = "", $offset = "") {
        $search_filters['photo_author_id'] = $photographer_id;
        return Photo::search($search_filters, $limit, $offset);
    }

    public static function search($search_filters, $limit = "", $offset = "") {
        // Accepts $_POST from search.php form and converts into conditions

        $conditions = [];
        $order_by = "";
        $joins = "";

        if (isset($search_filters['photo_author_id']) && !empty($search_filters['photo_author_id'])) {
            $conditions[Photo::get_table_prefix()."author_id"] = $search_filters['photo_author_id'];
        }

        if (isset($search_filters['results_per_page'])) $limit = $search_filters['results_per_page'] + 1;

        if (isset($search_filters['photo_id']) && !empty($search_filters['photo_id'])) {
            $conditions[Photo::get_table_prefix()."id"] = $search_filters['photo_id'];
            $limit = 1;
        }
        if (isset($search_filters['photo_title']) && !empty($search_filters['photo_title'])) {
            $conditions[Photo::get_table_prefix()."title"] = ['like' => [$search_filters['photo_title']]];
        }
        if (isset($search_filters['photo_subtitle']) && !empty($search_filters['photo_subtitle'])) {
            $conditions[Photo::get_table_prefix()."subtitle"] = ['like' => [$search_filters['photo_subtitle']]];
        }
        if (isset($search_filters['photo_text']) && !empty($search_filters['photo_text'])) {
            $conditions[Photo::get_table_prefix()."text"] = ['like' => [$search_filters['photo_text']]];
        }
        if (isset($search_filters['photo_author']) && !empty($search_filters['photo_author'])) {
            $joins = [
                ['type'       => 'full',
                 'join_table' => 'users',
                 'on'         => [Photo::get_table_prefix()."author_id" => User::get_table_prefix()."id"]]
            ];
            $conditions[User::get_table_prefix()."username"] = ['like' => [$search_filters['photo_author']]];
        }
        if (!empty($search_filters['photo_date_from']) && empty($search_filters['photo_date_to'])) {
            $conditions[Photo::get_table_prefix()."date"] = ['>=' => [$search_filters['photo_date_from']]];
        }
        if (empty($search_filters['photo_date_from']) && !empty($search_filters['photo_date_to'])) {
            $conditions[Photo::get_table_prefix()."date"] = ['<=' => [$search_filters['photo_date_to']]];
        }
        if (!empty($search_filters['photo_date_from']) && !empty($search_filters['photo_date_to'])) {
            $conditions[Photo::get_table_prefix()."date"] = ['between' => [$search_filters['photo_date_from'], $search_filters['photo_date_to']]];
        }

        return Photo::find("*", $conditions, $order_by, $limit, $offset, $joins);
    }
}

?>