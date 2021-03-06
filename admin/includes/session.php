<?php

class Session {

    private $signed_in;
    public $user_id;
    public $user_username;
    public $user_role;
    public $message;

    function __construct(){
        // Construct method runs on object creation
        // Start session and check if a user is logged in
        session_start();
        $this->check_login_details();
    }

    public function is_signed_in() {
        // Getter method, returns true or false from signed_in property
        return $this->signed_in;
    }

    public function login($user){
        // Sets session user ID to submitted user object's user ID and signed_in property true
        if ($user) {
            $this->user_username = $_SESSION['user_username'] = $user->user_username;
            $this->user_id       = $_SESSION['user_id'] = $user->user_id;
            $this->user_role     = $_SESSION['user_role'] = $user->user_role;
            $this->signed_in = true;
        }
    }

    public function logout(){
        // Destroys session variables, session object user ID, sets signed_in property to false
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role']);
        unset($this->user_id);
        $this->signed_in = false;
    }

    private function check_login_details(){
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']) && isset($_SESSION['user_role'])) {
            $this->user_id       = $_SESSION['user_id'];
            $this->user_username = $_SESSION['user_username'];
            $this->user_role = $_SESSION['user_role'];
            $this->signed_in = true;
        } else {
            unset($this->user_id);
            unset($this->user_username);
            unset($this->user_role);
            $this->signed_in = false;
        }
    }

    public function admin_access() {
        if ($this->user_is_admin() || $this->user_is_photographer()) return true;
        return false;
    }

    public function user_is_admin() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "Admin") return true;
        return false;
    }

    public function user_is_photographer() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "Photographer") return true;
        return false;
    }
} // end of class Session

$session = new Session();

?>