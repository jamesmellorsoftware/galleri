<?php

class User extends db_objects {

    protected static $db_table = "users";
    protected static $db_prefix = "user_";
    protected static $db_table_fields = array("user_id", "user_username", "user_password", "user_firstname", "user_lastname", "user_email", "user_banned", "user_role", "user_image");
    protected static $bulk_options = [
        ["value" => "delete",         "name"  => MODERATE_USERS_BULKOPTION_DELETE],
        ["value" => "ban",            "name"  => MODERATE_USERS_BULKOPTION_BAN],
        ["value" => "unban",          "name"  => MODERATE_USERS_BULKOPTION_UNBAN],
        ["value" => "admin",          "name"  => MODERATE_USERS_BULKOPTION_CHANGE_ADMIN],
        ["value" => "photographer",   "name"  => MODERATE_USERS_BULKOPTION_CHANGE_PHOTOGRAPHER],
        ["value" => "user",           "name"  => MODERATE_USERS_BULKOPTION_CHANGE_USER]
    ];
    protected static $search_filters = [
        "user_username",
        "user_firstname",
        "user_lastname",
        "user_email",
        "user_id",
        "user_role",
        "user_banned",
        "user_hasphoto",
        "results_per_page"
    ];

    // Initialise user properties
    public $user_id;
    public $user_username;
    public $user_password;
    public $user_firstname;
    public $user_lastname;
    public $user_email;
    public $user_banned;
    public $user_role;
    public $user_image;

    public $tmp_path;
    public $upload_dir = "img/users";
    public $img_placeholder = "https://via.placeholder.com/500/000000/FFFFFF/?text=No Photo";

    public function save(){
        if ($this->user_id) return $this->update_user();

        if (!empty($this->errors)) return false;

        if (empty($this->user_image) || empty($this->tmp_path)) {
            $this->errors[] = "File not available.";
            return false;
        }

        $target_path = SITE_ROOT . DS . $this->upload_dir . DS . $this->user_image;

        if (file_exists($target_path)) {
            $this->errors[] = "File {$this->user_image} already exists.";
            return false;
        }

        if (!move_uploaded_file($this->tmp_path, $target_path)) {
            $this->errors[] = "Error moving file. Folder probably lacks permissions. Contact administrator.";
            return false;
        }

        if (!$this->create()) {
            $this->errors[] = "Error creating user. Image uploaded but user not created.";
            return false;
        }

        unset($this->tmp_path);
        return true;
    }

    public function update_user(){
        // Update ONE record of corresponding class in database (condition: ID)
        // NOTE: class MUST be instantiated and properties set first before calling!

        if (!$this) return false;

        global $db;

        // Retrieve class properties and place into array to build SQL statement
        $properties_to_set = $this->set_properties();

        // Set WHERE condition to id = $this->id so only 1 record affected
        $conditions = [User::get_table_prefix() . "id" => $this->user_id];

        if (!$sql = $db->build_update(
            User::get_table_name(),
            $properties_to_set,
            $conditions,
            1)) return false;
        
        return $db->query($sql);
    }

    public function get_user_image(){
        // Getter function, retrieve $user_image or placeholder if it's not there
        return empty($this->user_image) ? $this->img_placeholder : $this->upload_dir . DS . $this->user_image;
    }

    public function set_file($file) {
        // $_FILES['uploaded_file'] is the accepted argument
        // Checks and validates file uploaded
        // Sets $this->user_image to the filename of the uploaded file
        // Sets $this->tmp_path to file temporary name

        if (empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "No file uploaded.";
            return false;
        } else if ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        }

        $this->user_image = basename($file['name']);
        $this->tmp_path   = $file['tmp_name'];

        return true;
    }

    public function delete_photo() {
        if (empty($this)) return false;

        if (!empty($this->errors)) return false;

        $target_path = SITE_ROOT . DS . $this->upload_dir . DS . $this->user_image;

        if (file_exists($target_path)) {
            if (!unlink($target_path)) return false;
        }

        return true;
    }

    public function save_photo(){
        // Uploads photo to correct directory if set_file() went through correctly

        if (!empty($this->errors)) return false;

        if (empty($this->user_image) || empty($this->tmp_path)) {
            $this->errors[] = "File not available.";
            return false;
        }

        $target_path = SITE_ROOT . DS . $this->upload_dir . DS . $this->user_image;

        if (file_exists($target_path)) {
            $this->errors[] = "File {$this->user_image} already exists.";
            return false;
        }

        if (move_uploaded_file($this->tmp_path, $target_path)) {
            unset($this->tmp_path);
            return true;
        } else {
            $this->errors[] = "Error moving file. Folder probably lacks permissions.";
            return false;
        }
    }

    public static function retrieve($username) {
        // Login checker - checks if user exists in DB and if password matches
        // If found, returns user details in array, otherwise returns false

        global $db;

        $sql = $db->build_select(
            self::$db_table,
            "*",
            ["user_username" => $username],
            "" ,
            1
        );

        $result_set = self::execute_query($sql);

        return !empty($result_set) ? array_shift($result_set) : false;
    }

    public static function find_photographers($limit = "", $offset = "") {
        return User::find_by_column("role", "Photographer", $limit, $offset);
    }

    public static function exists($username) {
        global $db;
        $sql = $db->build_select(
            self::$db_table,
            [['col' => 'user_username']],
            ["user_username" => $username],
            "" ,
            1);
        $result_set = self::execute_query($sql);
        return !empty($result_set) ? true : false;
    }

    public static function email_in_use($email) {
        global $db;
        $sql = $db->build_select(self::$db_table, "*", ["user_email" => $email], "" , 1);
        $result_set = self::execute_query($sql);
        return !empty($result_set) ? array_shift($result_set) : false;
    }

    public function verify_registration($user_image) {
        // Checks if registration data user values are valid
        // Checks:
        // - Email in use?
        // - Username in use?
        // - Mandatory field left empty?
        // - No image uploaded?

        $registration_errors = [
            "username"   => "",
            "password"   => "",
            "firstname"  => "",
            "email"      => ""
        ];

        $registration_errors = User::check_empty_inputs($this, $registration_errors);
        if (User::exists($this->user_username))    $registration_errors['username'] = "Username already in use.";
        if (User::email_in_use($this->user_email)) $registration_errors['email']    = "Email already in use.";

        $registration_errors["user_image"] = "";
        if (!$user_image || !is_array($user_image) || empty($user_image) || !is_uploaded_file($user_image['tmp_name'])) {
            $registration_errors['user_image'] = "Please select an image.";
        }

        return $registration_errors;

    }

    public function verify_edit($old_user_values) {
        // Checks if registration data user values are valid
        // Checks:
        // - Email changed and new email already in use?
        // - Username changed and new username already in use?
        // - Mandatory field left empty?

        $edit_user_errors = [
            "username"  => "",
            "password"  => "",
            "firstname" => "",
            "lastname" => "",
            "email"     => "",
            "role"      => "",
            "image"     => ""
        ];

        $edit_user_errors = User::check_empty_inputs($this, $edit_user_errors);

        if ($old_user_values->user_username != $this->user_username && User::exists($this->user_username)) {
            $edit_user_errors['username'] = "This username is already in use.";
        }

        if ($old_user_values->user_email != $this->user_email && User::email_in_use($this->user_email)) {
            $edit_user_errors['email'] = "This email address is already in use.";
        }

        return $edit_user_errors;

    }

    public function verify_login() {
        // Checks if login data user values are valid
        // Checks:
        // - Username exists?
        // - Mandatory field left empty?
        // - Password correct?

        $login_errors = ["username"  => "", "password"  => ""];

        $login_errors = User::check_empty_inputs($this, $login_errors);
        
        if (!empty($login_errors)) return $login_errors;

        $user_retrieved = User::retrieve($this->user_username);

        if (!$user_retrieved || empty($user_retrieved)) $login_errors['username'] = "Username does not exist.";

        if (!empty($login_errors)) return $login_errors;

        if (!password_verify($this->user_password, $user_retrieved->user_password)) $login_errors['password'] = "Incorrect password.";

        return $login_errors;

    }

    public static function get_name_from_id($id) {
        $result = User::find(
            [["col" => User::get_table_prefix()."firstname"],
            ["col" => User::get_table_prefix()."lastname"]],
            [User::get_table_prefix()."id" => $id],
            "",
            1);
        $result = array_shift($result);
        if (empty($result) || empty($result->user_firstname) || empty($result->user_lastname)) return $id;
        return $result->user_firstname . " " . $result->user_lastname;
    }

    public static function purge($user_ids) {
        // Delete from DB:
        // - All users from users table (1)
        // - All users' uploads in photo table and their corresponding comments, likes, and comment likes (2)
        // - All comments authored by the users in comments table and their corresponing comment likes (3)
        // - All comment likes authored by users (4)
        // - All likes authored by users (5)
        // Delete user's photo from the image directory (6)

        $users_uploaded_photos = Photo::find_photographer_gallery($user_ids);

        if (!empty($users_uploaded_photos)) {
            foreach ($users_uploaded_photos as $users_uploaded_photo) {
                $users_uploaded_photos_ids[] = $users_uploaded_photo->photo_id;
            }
            Photo::purge($users_uploaded_photos_ids); // (2)
        }
        
        Comment::purge_via_author_id($user_ids); // (3)
        Comment_Like::purge_via_author_id($user_ids); // (4)
        Like::purge_via_author_id($user_ids); // (5)

        // Delete user images (6)
        $users = User::find_all("", "", "", [User::get_table_prefix()."id" => $user_ids]);

        foreach ($users as $user) {
            $user_filename = $user->user_image;
            if (file_exists("../img/users/" . $user_filename)) unlink("../img/users/" . $user_filename);
        }

        User::purge_via_user_id($user_ids); // (1)

    }

    public static function purge_via_user_id($user_ids) {
        global $db;

        $conditions = [User::get_table_prefix()."id" => $user_ids];

        $sql = $db->build_delete(User::$db_table, $conditions);

        $db->query($sql);
    }

    public static function ban($user_ids) {
        global $db;

        $sql = $db->build_update(
            self::$db_table,
            [User::get_table_prefix()."banned" => 1],
            [User::get_table_prefix()."id" => $user_ids]
        );

        $db->query($sql);
    }

    public static function unban($user_ids) {
        global $db;

        $sql = $db->build_update(
            self::$db_table,
            [User::get_table_prefix()."banned" => 0],
            [User::get_table_prefix()."id" => $user_ids]
        );

        $db->query($sql);
    }

    public static function change_user_role($user_ids, $new_role = "") {
        
        switch ($new_role) {
            case "photographer":
                $new_role = "Photographer";
                break;
            case "admin":
                $new_role = "Admin";
                break;
            case "user":
            default:
                $new_role = "User";
                break;
        }
        
        global $db;

        $sql = $db->build_update(
            self::$db_table,
            [User::get_table_prefix()."role" => $new_role],
            [User::get_table_prefix()."id" => $user_ids]
        );

        $db->query($sql);
    }

    public static function search($search_filters, $limit = "", $offset = "") {
        // Accepts $_POST from search.php form and converts into conditions

        $conditions = [];
        $order_by = "";
        $joins = "";

        if (isset($search_filters['results_per_page'])) $limit = $search_filters['results_per_page'] + 1;

        if (isset($search_filters['user_role'])) {
            $user_roles = [];
            if (isset($search_filters['user_role']['admin']))        array_push($user_roles, "Admin");
            if (isset($search_filters['user_role']['photographer'])) array_push($user_roles, "Photographer");
            if (isset($search_filters['user_role']['user']))         array_push($user_roles, "User");
            $conditions[User::get_table_prefix()."role"]    = $user_roles;
        }
        if (isset($search_filters['user_banned']) && !empty($search_filters['user_banned'])) {
            $conditions[User::get_table_prefix()."banned"] = 1;
        }
        if (isset($search_filters['user_id']) && !empty($search_filters['user_id'])) {
            $conditions[User::get_table_prefix()."id"] = $search_filters['user_id'];
        }
        if (isset($search_filters['user_username']) && !empty($search_filters['user_username'])) {
            $conditions[User::get_table_prefix()."username"] = ['like' => [$search_filters['user_username']]];
        }
        if (isset($search_filters['user_firstname']) && !empty($search_filters['user_firstname'])) {
            $conditions[User::get_table_prefix()."firstname"] = ['like' => [$search_filters['user_firstname']]];
        }
        if (isset($search_filters['user_lastname']) && !empty($search_filters['user_lastname'])) {
            $conditions[User::get_table_prefix()."lastname"] = ['like' => [$search_filters['user_lastname']]];
        }
        if (isset($search_filters['user_hasphoto']) && !empty($search_filters['user_hasphoto'])) {
            $conditions[User::get_table_prefix()."image"] = ['empty' => 0];
        }
        
        return User::find("*", $conditions, $order_by, $limit, $offset, $joins);
    }

} // end of class User

?>