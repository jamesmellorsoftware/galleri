<?php

require_once("config.php");

class Database {
    public $connection; // gets referred to later in open db connection function

    function __construct(){
        // Open connection automatically on object creation
        $this->open_db_connection();
    }

    public function open_db_connection(){
        // Create new connection to database
        // Constants DB_HOST, DB_USER, DB_PASS, DB_NAME are set in config.php
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_errno) die("DB CONNECTION FAILED: " . $this->connection->connect_error);
    }

    // ==== SQL STATEMENT BUILDERS =====================================================================
    // Makes building SQL statements easier, fewer lines of code, less repetition
    // Instead of writing complex queries with joins and repeating SQL commands over and over,
    // just create corresponding arrays with desired results, then pass them to the builders

    // COMMON ARGUMENTS:
    // $limit                  - String with number e.g. "1" or integer for LIMIT clause
    //                           NOTE: No default! You may want to delete multiple records
    //                           and defaulting to 1 would make that impossible
    //                           If you only want 1 record to delete, set the limit to 1
    // $offset                 - integer, offset amount
    // $db_table               - Table to search, string

    public function build_select($db_table, $retrieve_columns = [], $search_cols_and_values = [], $order_by = [], $limit = "", $offset = "", $joins = []){
        // ARGUMENTS:
        // $retrieve_columns       - 2D assoc array with columns to fetch
        //                           e.g. [ ['col' => 'photo_id', 'operation' => 'sum'], [...], ...]
        //                           (Leave operation empty or not set to just select the col)
        // $search_cols_and_values - 2D assoc array with columns and search criteria
        //                           e.g. ['col' => 'value', ... ]
        //                           or for multiple values for the same column (IN operator):
        //                           e.g. ['col' => ['value 1', 'value 2', ... ], ... ]
        //                           or for between operator:
        //                           e.g. ['col' => ['between' => ['value 1', 'value 2']], ... ]
        //                           or for like % % operator:
        //                           e.g. ['col' => ['like' => ['value 1']], ... ]
        // $order_by               - 2D assoc array with order by and direction,
        //                           e.g. [ ['col' => 'dir'], ['photo_id' => 'DESC'], [...], ... ]
        // $joins                  - 2D assoc array with column & ON statement
        //                           e.g. [ ['type' => 'full', 'join_table' => 'table2', 'on' => ['table1.column1' => 'table2.column2', ...]], [...], ... ]
        
        
        // Returns SQL statement as string

        // Return false if vital data missing
        if (empty($db_table)) return false;

        $sql = "SELECT ";

        // Which columns are being selected?
        if (empty($retrieve_columns) || $retrieve_columns == "*") {
            // If no retrieve columns set, retrieve all
            $sql.= "* ";
        } else {
            // Otherwise build query with selected columns
            $i = 0;
            foreach ($retrieve_columns as $retrieve_column) {
                if ($i !== 0) $sql.= ", "; // add comma if more than one column retrieved

                // Check if sum, count, or avg
                if (isset($retrieve_column['operation']) && !empty($retrieve_column['operation'])) {
                    switch($retrieve_column['operation']) {
                        case "avg":
                            $sql.= "AVG(" . $retrieve_column['col'] . ") ";
                            break;
                        case "count":
                            $sql.= "COUNT(" . $retrieve_column['col'] . ") ";
                            break;
                        case "sum":
                            $sql.= "SUM(" . $retrieve_column['col'] . ") ";
                            break;
                    }
                } else { $sql.= $retrieve_column['col'] . " "; }
                
                $i++;
            }
        }

        // From which table? Set by class invoked from db_objects
        $sql.= "FROM " . $db_table . " ";
        
        // Joins
        if (!empty($joins)) {
            $i = 0;
            foreach ($joins as $join) {
                // Get join type, default is full outer
                if (isset($join['type'])) {
                    switch ($join['type']) {
                        case "in":
                            $sql.= "INNER ";
                            break;
                        case "l":
                            $sql.= "LEFT OUTER ";
                            break;
                        case "r":
                            $sql.= "RIGHT OUTER ";
                            break;
                    }
                }
                
                // to which table?
                $sql.= "JOIN " . $join['join_table'] . " ";

                // on what columns?
                $sql.= "ON ";

                $i = 0;
                foreach ($join['on'] as $col1 => $col2) {
                    if ($i != 0) $sql.= " AND ";
                    $sql.= $db_table . "." . $col1 . " = " . $join['join_table'] . "." . $col2 . " ";
                    $i++;
                }
            }
        }

        // Conditions
        if (!empty($search_cols_and_values)) {
            $sql.= "WHERE ";
            $i = 0;
            foreach ($search_cols_and_values as $col => $value) {
                if ($i !== 0) $sql.= " AND ";
                if (is_array($value)) {
                    // i.e. if we're expecting an in, between, like, or <><=>= operator
                    if (array_key_exists('between', $value)) {
                        $sql.= $col . " BETWEEN '" . $value['between'][0] . "' AND '" . $value['between'][1] . "' ";
                    } elseif (array_key_exists('like', $value)) {
                        $sql.= $col . " LIKE '%" . $value['like'][0] . "%' ";
                    } elseif (array_key_exists('<', $value)) {
                        $sql.= $col . " < '" . $value['<'][0] . "' ";
                    } elseif (array_key_exists('>', $value)) {
                        $sql.= $col . " > '" . $value['>'][0] . "' ";
                    } elseif (array_key_exists('<=', $value)) {
                        $sql.= $col . " <= '" . $value['<='][0] . "' ";
                    } elseif (array_key_exists('>=', $value)) {
                        $sql.= $col . " >= '" . $value['>='][0] . "' ";
                    } elseif (array_key_exists('empty', $value)) {
                        $sql.= $col . " IS";
                        if ($value['empty'] == 0) $sql.= " NOT";
                        $sql.= " NULL ";
                    } else {
                        $sql.= (count($value) == 1) ? $col . " = '" . $value[0] . "' " : $col . " IN ('" . implode("','", $value) . "') ";
                    }
                } else {
                    $sql.= $col . " = '" . $value . "' ";
                }
                $i++;
            }
        }

        // Order by
        if (!empty($order_by)) {
            $sql.= "ORDER BY ";
            $i = 0;
            foreach ($order_by as $col => $direction) {
                if ($i !== 0) $sql.= ", ";
                $sql.= $col . " " . $direction . " ";
                $i++;
            }
        }

        // Limit
        if (!empty($limit)) $sql.= "LIMIT " . $limit . " ";

        // Offset
        if (!empty($offset)) $sql.= "OFFSET " . $offset . " ";

        // Return SQL string or false if it wasn't built
        return ($sql) ? $sql : false;
    }

    public function build_insert($db_table, $cols_and_values = []) {
        // ARGUMENTS:
        // $cols_and_values        - Indexed array with columns & values, keys = cols, values = values
        //                           e.g. ["photo_id" => 1, "photo_title" => "Title 1", ...]

        if (empty($db_table) || empty($cols_and_values)) return false;

        $sql = "INSERT INTO ";
        $sql.= $db_table . " ";
        $sql.= "(" . implode(",", array_keys($cols_and_values)) . ")";
        $sql.= "VALUES ";
        $sql.= "('" . implode("','", array_values($cols_and_values)) . "')";

        // Return SQL string or false if it wasn't built
        return ($sql) ? $sql : false;
    }

    public function build_update($db_table, $set_cols_and_values = [], $conditions = [], $limit = "") {
        // ARGUMENTS:
        // $cols_and_values        - Indexed array with columns and values for SET command
        //                           e.g. ["photo_id" => 1, "photo_title" => "Title 1", ...]
        // $conditions             - Indexed array with columns and values for WHERE clause
        //                           e.g. ["photo_id" => 1, "photo_title" => "Title 1", ...]

        if (empty($db_table) || empty($set_cols_and_values)) return false;

        $sql = "UPDATE " . $db_table . " ";

        // Set
        $sql.= "SET ";
        $i = 0;
        foreach ($set_cols_and_values as $col => $value) {
            if ($i !== 0) $sql.= ", ";
            $sql.= $col . " = '" . $value . "' ";
            $i++;
        }

        // Conditions
        if (!empty($conditions)) {
            $sql.= "WHERE ";
            $i = 0;
            foreach ($conditions as $col => $value) {
                if ($i !== 0) $sql.= " AND ";
                if (is_array($value)) {
                    $sql.= (count($value) == 1) ? $col . " = '" . $value[0] . "' " : $col . " IN ('" . implode("','", $value) . "') ";
                } else {
                    $sql.= $col . " = '" . $value . "' ";
                }
                $i++;
            }
        }

        // Limit
        if (!empty($limit)) $sql.= "LIMIT {$limit} ";

        // Return SQL string or false if it wasn't built
        return ($sql) ? $sql : false;
    }

    public function build_delete($db_table, $conditions = [], $limit = "") {
        // ARGUMENTS:
        // $conditions -             2D assoc array with columns and search criteria
        //                           e.g. ['col' => 'value', ... ]
        //                           or for multiple values for the same column (OR):
        //                           e.g. ['col' => ['value 1', 'value 2', ... ], ... ]
        
        if (empty($conditions) || empty($db_table)) return false;

        $sql = "DELETE FROM " . $db_table . " ";

        // Conditions
        if (!empty($conditions)) {
            $sql.= "WHERE ";
            $i = 0;
            foreach ($conditions as $col => $value) {
                if ($i !== 0) $sql.= " AND ";
                if (is_array($value)) {
                    $sql.= (count($value) == 1) ? $col . " = '" . $value[0] . "' " : $col . " IN ('" . implode("','", $value) . "') ";
                } else {
                    $sql.= $col . " = '" . $value . "' ";
                }
                $i++;
            }
        }

        // Limit
        if (!empty($limit)) $sql.= "LIMIT {$limit}} ";

        // Return SQL string or false if it wasn't built
        return ($sql) ? $sql : false;
    }
    // =================================================================================================

    public function query($sql){
        // Automates performing SQL query (mysqli_query($sql)) and checking it (helper function)
        // Exists to avoid repeating the if (!$result) die(...) commands over and over
        // Kills process on SQL failure and returns SQL error
        // Returns result of mysqli_query() (object) if successful
        // Argument accepted: SQL query string e.g. "SELECT * FROM users"

        $result = $this->connection->query($sql);

        if (!$result) die("QUERY FAILED: " . $this->connection->error);

        return $result;
    }

    public function inserted_id(){
        // Returns the last auto-generated ID
        return $this->connection->insert_id;
    }
}

$db = new Database();

?>