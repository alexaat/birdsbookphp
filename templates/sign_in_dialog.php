<?php
//sign_in_dialog.php

session_start();
?>

<?php
        if(isset($_GET['redirect-sign-up'])){
        $_SESSION['sign_in_error'] = '';
	    $_SESSION["modal_display_sign_in"] = 'none';
	    $_SESSION["modal_display_sign_up"] = 'block';
	    header("Location: ../index.php");
    }


    if(isset($_GET['close-dialog-click'])){
        $_SESSION['sign_in_error'] = '';
	    $_SESSION["modal_display_sign_in"] = 'none';
	    header("Location: ../index.php");
    }
    
    if(isset($_POST['submit'])){
        //get user
        require  __DIR__.'/../db/sql.php';
        $user = getUser($_POST['nick_name'], $_POST['password']);

        if($user){
            
            //save session to db
            require __DIR__.'/../util/uuid.php';
            $uuid = getUUID();
            $user_id = $user['id'];
            
            if(saveSessionToDatabase($user_id, $uuid)){
                //set cookie
                require __DIR__.'/../util/cookie.php';
                set_cookie($uuid);
                $_SESSION['sign_in_error'] = '';
	            header("Location: ../index.php");
            }
            
            $_SESSION["current_user"] = $user;
            $_SESSION["modal_display_sign_in"] = "none";
            
        }else {
           $error = 'could not find user'; 
           $_SESSION["sign_in_error"] = $error;
           header("Location: ../index.php");
        }
      }
?>

<div class='modal' style='display: <?php  echo $_SESSION["modal_display_sign_in"] ?> '>
    <div class='modal-content' style='width: 450px;'>
        
        <div class='dialog-header'>
            <div class='dialog-title'>Sign In</div>
            <form action='templates/sign_in_dialog.php' method='GET' >
                <input type='submit' value = '' class='close' name='close-dialog-click'/>
            </form>
        </div>

        <form id='sign-in-submit' action="templates/sign_in_dialog.php" method='POST' class='auth-dialog-content'>
            <?php
                if($_SESSION["sign_in_error"]){
                    echo "<div class='error-message'>{$_SESSION['sign_in_error']}</div>";
                }
            ?>     
            <input type="text" id="nick_name" name='nick_name' placeholder='nick name' class='auth-input-field'><br><br>
            <input type="password" id="password" name='password' placeholder='password' class='auth-input-field'><br><br>
            <input type="submit" value="Submit" name='submit' formmethod='POST'  class='form-submit-button'>
        </form> 
        <form id='form-redirect-sign-up' action="templates/sign_in_dialog.php" method="GET">
            <input type="submit" value="Don't have account?" name="redirect-sign-up" class='link'>
        </form>    
    </div>
</div>