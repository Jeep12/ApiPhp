<?php
class UserHelper{
    function __construct() {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            
        }
    }
    public function login($user) {
        $_SESSION['USER_ID'] = $user->id;
        $_SESSION['USERNAME'] = $user->nombre;
        $_SESSION['ADMIN']=$user->admin;
        
    }

    public function checkLoggedIn() {
     
        if (!empty($_SESSION['USER_ID'])) {
           return  true;
            
        }else {
           return  false;

        }
    }
    function admin(){
        if(isset($_SESSION['ADMIN'])){
            if ($_SESSION['ADMIN']==1){
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    function logout() {
        session_destroy();
        header("Location: " . BASE_URL);
    } 
}
