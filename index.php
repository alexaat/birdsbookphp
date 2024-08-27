<?php
    //index.php
    session_start();
    
    
    if(isset($_GET['new_post_button_click'])){
        $modal_display = 'block';
        $_SESSION["modal_display"] = 'block';
    }
    
    if(isset($_GET['close-dialog-click'])){
        $modal_display = 'none';
        $_SESSION["modal_display"] = 'none';
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
    
    <?php
    require __DIR__ . '/util/cookie.php';
    $session = get_cookie();
    
    require __DIR__ . '/db/sql.php';
    $user = getUserBySession($session);
    
    if($user){
        $_SESSION["current_user"] = $user;
    } 


    echo "<div class='posts-container'>";
    
    require __DIR__ . '/templates/post.php';
	$posts = getPosts();
    foreach ($posts as $key => $value) {
         renderPost($value);
    }
    
    echo "</div>";
    
    include('templates/header.php');
    
    require __DIR__.'/templates/add_post_dialog.php';
    require __DIR__.'/templates/sign_in_dialog.php';
    require __DIR__.'/templates/sign_up_dialog.php';
    
    ?>

</body>
</html>