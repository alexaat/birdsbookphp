<?php
//sign_up_dialog.php

session_start();
?>

<?php 
        if(isset($_GET['redirect-sign-in'])){
            $_SESSION['sign_in_error'] = '';
    	    $_SESSION["modal_display_sign_in"] = 'block';
    	    $_SESSION["modal_display_sign_up"] = 'none';
    	    unset($_SESSION["sign_up_values"]);
    	    unset($_SESSION['sign_up_errors']);
    	    //$_SESSION["sign_up_error_nick_name"] = '';
            //$_SESSION["sign_up_error_error_email"] = '';
    	    header("Location: ../index.php");
        }
        
        if(isset($_GET['close-dialog-click'])){
            $_SESSION["modal_display_sign_up"] = 'none';
            $_SESSION["modal_display_sign_in"] = 'none';
            //$_SESSION["sign_up_error_nick_name"] = '';
            //$_SESSION["sign_up_error_error_email"] = '';
            unset($_SESSION['sign_up_errors']);
            unset($_SESSION["sign_up_values"]);
            header("Location: ../index.php");
        }
        
    $error_nick_name = '';
    $error_email = '';
    $error_password = '';
    
    $sign_up_errors = $_SESSION["sign_up_errors"];

    if(isset($_POST['submit'])){
        
        unset($_SESSION['sign_up_errors']);
        unset($sign_up_errors);
        //$_SESSION["sign_up_error_nick_name"] = '';
        //$_SESSION["sign_up_error_error_email"] = '';
        
        $nick_name = $_POST['nick_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        require  __DIR__.'/../db/sql.php';
        
        
        //Validation
        $nick_name = trim($nick_name);
        $email = trim($email);
        $password = trim($password);

        if(strlen($nick_name) < 2 || strlen($nick_name) > 50){
             $sign_up_errors['nick_name'] = 'Nick name must be between 2 and 50 characters long';
        } 
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sign_up_errors['email'] = 'Invalid email';
        }
        
        if(strlen($password) < 6 || strlen($password) > 50){
             $sign_up_errors['password'] = 'password must be between 6 and 50 characters long';
        } 
        
        if(array_filter($sign_up_errors)){
            $_SESSION["sign_up_errors"] = $sign_up_errors;
            $_SESSION["sign_up_values"] = ['nick_name' => $nick_name, 'email' => $email];
            header("Location: ../index.php");
            
            
        } else {
            $result = saveUserToDatabase($nick_name, $email, $password);
            
            if($result['error']){
                $error = $result['error']['error'];

            if(strpos($error, "Duplicate") !== false){
                
                if(strpos($error, "nick_name") !== false){
                    $sign_up_errors['nick_name'] = 'Nick name aleady exists';
                    $_SESSION["sign_up_errors"] = $sign_up_errors;
                    //$error_nick_name = 'nick name aleady exists...';
                    //$_SESSION["sign_up_error_nick_name"] =  $error_nick_name;
                }
                if(strpos($error, "email") !== false){
                     $sign_up_errors['email'] = 'Email already exists';
                     $_SESSION["sign_up_errors"] = $sign_up_errors;
                    //$error_email = 'email already exists...';
                    //$_SESSION["sign_up_error_error_email"] = $error_email; 
                }
            }
            $_SESSION["sign_up_values"] = ['nick_name' => $nick_name, 'email' => $email];
            header("Location: ../index.php");
            
        } elseif ($result['last_id']) {
            $user_id = $result['last_id'];    
            require __DIR__.'/../util/uuid.php';
            $uuid = getUUID();
            
            if(saveSessionToDatabase($user_id, $uuid)){
                //set cookie
                require __DIR__.'/../util/cookie.php';
                set_cookie($uuid);
                
                $user = getUserBySession($uuid);
                if($user){
                    $_SESSION['current_user'] = $user;
                }
                
                $_SESSION["modal_display_sign_up"] = 'none';
                $_SESSION["modal_display_sign_in"] = 'none';
                //$_SESSION["sign_up_error_nick_name"] = '';
                //$_SESSION["sign_up_error_error_email"] = '';
                unset($_SESSION['sign_up_errors']);
                unset($_SESSION["sign_up_values"]);
                
                header("Location: ../index.php");
            }
        }
            
        }

    }
        
?>

<div class='modal' style='display: <?php  echo $_SESSION["modal_display_sign_up"] ?> '>
    <div class='modal-content' style='width: 450px;'>
        
        <div class='dialog-header'>
            <div class='dialog-title'>Sign Up</div>
            <form action='templates/sign_up_dialog.php' method='GET' >
                 <input type='submit' value = '' class='close' name='close-dialog-click'/>
            </form>
        </div>
            <form action="templates/sign_up_dialog.php" method='POST' class='auth-dialog-content'>
                <?php /* if($_SESSION["sign_up_error_nick_name"]) {
                        echo "<div class='error-message'>{$_SESSION['sign_up_error_nick_name']}</div>";
                    }
                */?>
                <div class='error-message'>
                    <?php echo $sign_up_errors['nick_name'] ?>
                </div> 
                <input
                    type="text"
                    id="nick_name"
                    name='nick_name'
                    placeholder='nick name'
                    class='auth-input-field'
                    value='<?php echo htmlspecialchars($_SESSION["sign_up_values"]['nick_name']) ?>'
                    >
                    <br><br>
                <?php /* if($_SESSION["sign_up_error_error_email"] ) {
                    echo "<div class='error-message'>{$_SESSION['sign_up_error_error_email']}</div>";
                    }
                */?>
                <div class='error-message'>
                    <?php echo $sign_up_errors['email'] ?>
                </div> 
                <input
                    type="text"
                    id="email"
                    name='email'
                    placeholder='email'
                    class='auth-input-field'
                    value='<?php echo htmlspecialchars($_SESSION["sign_up_values"]['email']) ?>'
                    >
                    <br><br>
                
                <div class='error-message'>
                    <?php echo $sign_up_errors['password'] ?> 
                </div> 
                 
                <input type="password" id="password" name='password' placeholder='password'  class='auth-input-field'><br><br>
                <input type="submit" value="Submit" name='submit' formmethod='POST' class='form-submit-button'>
            </form>
            
            <form action="templates/sign_up_dialog.php" method="GET">
                <input type="submit" value="Already have an account?" name="redirect-sign-in" class='link'>
            </form>
       
    </div>
</div>
