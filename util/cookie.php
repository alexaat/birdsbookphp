<?php

    //cookie.php
    function set_cookie($uuid){
        $cookie_name = "BIRDS_BOOK_SESSION_ID";
        // 1 day cookie
        setcookie($cookie_name, $uuid, time() + (86400 * 1), "/");
    }
    
    function get_cookie(){
        $cookie_name = "BIRDS_BOOK_SESSION_ID";
        return $_COOKIE[$cookie_name];
    }
    
    function delete_cookie(){
        $cookie_name = "BIRDS_BOOK_SESSION_ID";
        setcookie($cookie_name, '', -3600, "/");
    }   

?>