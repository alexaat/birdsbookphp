<?php 
//header.php
session_start();
?>

<?php

    if(isset($_GET['sign_in_button_click'])){
        $modal_display_sign_in = 'block';
        $_SESSION["modal_display_sign_in"] = 'block';
        header("Location: ../index.php");  
    }

?>

<nav class='nav-bar'>
    
    <div class='app-title'>Birds Book </div>
    
    <a href='./index.php'>
        <div
            class='nav-bar-icon clickable'
            style="background-image: url('icons/home.png');">
        </div>
    </a>
    <?php
        $current_user = $_SESSION["current_user"];
        
        if($current_user){
            echo "<form action='index.php' method='GET' >
                <input type='submit' name='new_post_button_click' value='NEW POST' class='new-post-button clickable'/>
            </form>";
        }
        
        if($current_user){
            
            if($current_user['image']){
                echo "
                    <a href='./profile.php'>
                        <div class='avatar clickable' style='background-image: url(./profiles/{$current_user['image']});'>
                        </div>
                    </a>
                ";
                
            } else {
                $nick_name = htmlspecialchars($current_user['nick_name']);
                echo "
                    <a href='./profile.php'>
                        <div class='avatar clickable'>
                            ${nick_name[0]}
                        </div>
                    </a>
                ";
            }           

            echo "<a href='http://alexaat.com/birdsbook/php/templates/signout.php' id='sign-out-link'>
                    <div class='sign-out-icon nav-bar-icon clickable'></div>
                 </a>";
        } else {
            echo "<form action='templates/header.php' method='GET'> 
                    <input type='submit' name='sign_in_button_click' value=''  class='nav-bar-icon logo-button clickable' />
                  </form>
            ";
            
            
        }
    ?>
    

</nav>