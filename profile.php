<?php
    //profile.php
    session_start();

    if(isset($_GET['modal_display_profile_image'])){
        $_SESSION["modal_display_profile_image"] = 'block';
    }
    
    if(isset($_GET['close-dialog-click'])){
        $_SESSION["modal_display_profile_image"] = 'none';
    }
    
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>birdbook</title>
</head>
<body>



<div class='profile-page-content'>
    <div class='bio'>
        <?php
            $profile_id = $_GET['user_id'];
            if($profile_id) {
                $_SESSION["profile_user"] = [id => "$profile_id"];
            } else {
                   $_SESSION["profile_user"] = $_SESSION["current_user"];
            }
            require __DIR__ . '/db/sql.php';
            require __DIR__ . '/templates/bio.php';
        ?>
        
    </div>
    <div class='profile-posts-container'>
        
        <?php
            $user = $_SESSION["current_user"];
            require __DIR__ . '/templates/post.php';
            $filter = ['user_id' => $user['id']];
            if($profile_id){
                 $filter = ['user_id' => "$profile_id"];
            }
            $posts = getPostsFilter($filter);
            
            if(!$posts || sizeof($posts)){
                echo "<div class='message-card'>You don't have any posts yet</div>";   
            } else {
                foreach ($posts as $key => $value) {
                    renderPost($value);
                }
                
            }
        
        ?>
    </div>
    
    
</div>


<?php
    include('templates/header.php');
?>

<?php
    require __DIR__.'/templates/profile_image_dialog.php';
    
?>
</body>
</html>