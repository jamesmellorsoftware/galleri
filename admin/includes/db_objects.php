<?php

class db_objects {
    public $errors = [];
    public $upload_errors = array(
        UPLOAD_ERR_OK           => "There is no error",
        UPLOAD_ERR_INI_SIZE		=> "The uploaded file exceeds the upload_max_filesize directive in php.ini",
        UPLOAD_ERR_FORM_SIZE    => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        UPLOAD_ERR_PARTIAL      => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE      => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION    => "A PHP extension stopped the file upload."
    );
    
    public static function get_table_name() {
        return static::$db_table;
    }

    public static function get_table_prefix() {
        return static::$db_prefix;
    }

    public static function get_bulk_options() {
        return static::$bulk_options;
    }

    public static function get_search_filters() {
        return static::$search_filters;
    }

    public static function execute_query($sql){
        // Takes SQL statement string e.g. "SELECT * FROM users ... "
        // Sends to DB, returns SQL results, converts to indexed array of instances of objects e.g.
        // [0] => User Object ( [user_id] => 1 [user_username] => JohnSmith ... )
        // [1] => User Object ( [user_id] => 2 [user_username] => JaneDoe ... )

        global $db;
        
        $query_results = $db->query($sql);

        // Loop through assoc array from query results and instantiate object for every row
        $result_object_set = [];
        while ($row = mysqli_fetch_array($query_results)) $result_object_set[] = static::retrieved_row_to_object_instance($row);

        return $result_object_set;

    }

    public static function retrieved_row_to_object_instance($row) {
        // Creates instance of class called from mysqli_query() results

        $called_class = get_called_class();

        $new_instance_from_object = new $called_class;

        foreach ($row as $col => $value) {
            // If the class actually has the property retrieved from DB col, assign it
            if ($new_instance_from_object->has_attribute($col)) $new_instance_from_object->$col = $value;
        }

        return $new_instance_from_object;
    }

    protected function has_attribute($attribute) {
        // Check if the called class has a property or not
        return (property_exists(get_called_class(), $attribute)) ? true : false;
    }

    public static function count_all() {
        global $db;
        if (!$sql = $db->build_select(static::$db_table, ["col" => "*", "operation" => "count"])) return false;
        $result_set = $db->query($sql);
        $row = $db->connection::fetch_assoc($result_set);
        return array_shift($row);
    }

    public static function find($retrieve_cols = "*", $conditions = "", $order_by = "", $limit = "", $offset = "", $joins = ""){
        global $db;
        $sql = $db->build_select(static::$db_table, $retrieve_cols, $conditions, $order_by, $limit, $offset, $joins);
        return static::execute_query($sql);
    }

    public static function find_all($pagination_limit = "", $offset = "", $joins = "", $conditions = ""){
        global $db;
        $sql = $db->build_select(static::$db_table, "*", $conditions, "", $pagination_limit, $offset, $joins);
        return static::execute_query($sql);
    }

    public static function find_by_id($id){
        global $db;
        $sql = $db->build_select(static::$db_table, "*", [static::$db_prefix."id" => $id], "", 1, "");
        $result = static::execute_query($sql);
        return !empty($result) ? array_shift($result) : false;
    }

    public static function find_by_column($column, $value, $limit = "", $offset = "", $joins = ""){
        global $db;
        $sql = $db->build_select(static::$db_table, "*", [static::$db_prefix.$column => $value], "", $limit, $offset, $joins);
        $result = static::execute_query($sql);
        return !empty($result) ? $result : false;
    }

    public static function find_by_column_multiresult($column, $values, $limit = "", $offset = ""){
        // Executes SQL query with multiple potential values for the same column (i.e. IN statement)

        global $db;

        $search_cols_and_values = [];
        $search_cols_and_values[static::$db_prefix.$column] = [];
        foreach ($values as $value) array_push($search_cols_and_values[static::$db_prefix.$column], $value);

        $sql = $db->build_select(static::$db_table, "*", $search_cols_and_values, "", $limit, $offset);

        $result = static::execute_query($sql);

        return !empty($result) ? $result : false;
    }

    protected function set_properties() {
        // Creates assoc array of object instance properties and values e.g. ["photo_id" => 3, ... ]
        // Loops through all DB fields defined in corresponding object
        // Checks if $this instance actually has the property set and assigns it
        // Keys are DB columns, values are their corresponding values
        // Avoids repeating assignation of object properties to arrays for processing
        // and also checks if the object actually has the fields set in db_table_fields

        $properties = array();

        foreach (static::$db_table_fields as $db_table_field) {
            if (property_exists($this, $db_table_field)) {
                if (!empty($this->$db_table_field)) $properties[$db_table_field] = $this->$db_table_field;
            }
        }

        return $properties;
    }

    public function create(){
        // Insert a record of corresponding class into database
        // NOTE: class MUST be instantiated and properties set first before calling!

        if (!$this) return false;

        global $db;

        // Retrieve called class' properties to get assoc array of columns => values to insert
        $properties = $this->set_properties();

        // Build insert query
        if (!$sql = $db->build_insert(static::$db_table, $properties)) return false;

        if($db->query($sql)) {
            // Set ID property of this class instance to the newest created ID
            // because it is the only property generated by the DB itself
            $this->{static::$db_prefix . "id"} = $db->inserted_id();
            return true;
        }
        
        return false;
    }

    public function update(){
        // Update ONE record of corresponding class in database (condition: ID)
        // NOTE: class MUST be instantiated and properties set first before calling!

        if (!$this) return false;

        global $db;

        // Retrieve class properties and place into array to build SQL statement
        $properties_to_set = $this->set_properties();

        // Set WHERE condition to id = $this->id so only 1 record affected
        $conditions = [static::$db_prefix . "id" => $this->static::$db_prefix."id"];

        if (!$sql = $db->build_update(static::$db_table, $properties_to_set, $conditions, 1)) return false;
        
        return $db->query($sql);
    }

    public function delete(){
        // Delete ONE record of corresponding class from database (condition: ID)
        // NOTE: class MUST be instantiated and properties set first before calling!

        global $db;

        // Set WHERE condition to id = $this->id so only 1 record affected
        $delete_conditions = [static::$db_prefix . "id" => $db->$this->{static::$db_prefix . "id"}];

        // Limit to 1 record to ensure no extra records are accidentally deletd
        if (!$sql = $db->build_delete(static::$db_table, $delete_conditions, 1)) return false;

        $db->query($sql);

        return $db->connection::affected_rows($db->connection) == 1 ? true : false;
    }

    public static function delete_multiple($col = "", $id = ""){
        // Delete multiple records of corresponding class from database (condition: ID)
        // Accepts column, string
        // Accepts IDs, single integer or array of integers

        global $db;

        // Set WHERE condition to id = $this->id so only 1 record affected
        $delete_conditions = [$col => $id];

        // Limit to 1 record to ensure no extra records are accidentally deletd
        if (!$sql = $db->build_delete(static::$db_table, $delete_conditions)) return false;

        $db->query($sql);

        return $db->connection::affected_rows($db->connection) == 1 ? true : false;
    }

    

    public static function check_empty_form($array_to_check) {
        // (Helper function, accepts an indexed array)
        // Loops through 1D array to check if it's correct
        $errors = [];

        foreach ($array_to_check as $key => $value) {
            if (empty($value)) $errors[$key] = "Please fill in all fields marked with *.";
        }

        return $errors;
    }

    public static function check_empty_inputs($object_to_check, $expected_inputs) {
        // (Helper function, accepts an object)
        // Loops through object to check if it's correct
        // $expected_inputs accepts an array for the inputs expected to NOT be blank
        $errors = [];

        foreach ($expected_inputs as $expected_input => $value) {
            $expected_property = static::$db_prefix . $expected_input;
            if (empty($object_to_check->$expected_property)) $errors[$expected_input] = "Please fill in all fields marked with *.";
        }

        return $errors;
    }

    public static function errors_in_form($errors) {
        // Accepts an indexed array of errors from a form submission
        // Checks if the array is empty or not
        // If not empty, errors are present, return true, otherwise return false

        foreach ($errors as $error) {
            if (!empty($error)) return true;
        }

        return false;
    }
}

?>