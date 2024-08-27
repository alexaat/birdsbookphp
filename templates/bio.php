<?php
//bio.php
  session_start();
?>

<h2>Bio </h2>

<div class='bio-content'>
<ul>
<?php
    $profile_user = $_SESSION["profile_user"];
    
    if($profile_user && $profile_user['nick_name']){
        $profile_user = $_SESSION["current_user"];
    } else {
        $id = $profile_user['id'];
        $profile_user = getUserById($id);
    }
    
    $nick_name = htmlspecialchars($profile_user['nick_name']);
    $email = htmlspecialchars($profile_user['email']);
    $about = htmlspecialchars($profile_user['about']);
    $image = htmlspecialchars($profile_user['image']);
   
    $buttonValue = '';

    if($image){
            echo "
             <form action='profile.php' method='GET'>
                 <input
                    type='submit'
                    name='modal_display_profile_image'
                    value=''
                    class='profile-image clickable'
                    style='background-image: url(./profiles/$image);' />

            </form>
            
            
            ";
    } else{
        $buttonValue = $nick_name[0];
        if($profile_user['id'] ===  $_SESSION["current_user"]['id']){
            echo "
                <li>
                    <form action='profile.php' method='GET'>
                        <input type='submit' name='modal_display_profile_image' value=$buttonValue class='avatar profile-image clickable' style='font-size: 64px;' />
                    </form>
                </li>
            ";
        } else {
            echo "
                <li>
                    <div class='avatar profile-image' style='font-size: 64px;'> $buttonValue
                    </div>
                </li>
            ";
        } 
    }
    
    echo "<li>".$nick_name."</li>";
    if($profile_user['id'] ===  $_SESSION["current_user"]['id']){
          echo "<li>".$email."</li>";
    }
  
    echo "<li>".$about."</li>";
?>
</ul>
</div>

