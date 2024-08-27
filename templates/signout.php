<?php 
//signout.php
session_start();

?>
<?php
        require __DIR__.'/../util/cookie.php';
        $cookie = get_cookie();
        require  __DIR__.'/../db/sql.php';
        deleteSessionFromDatabase($cookie);
        delete_cookie();
        
        session_unset();
        session_destroy(); 

        header("Location: ../index.php");
?>