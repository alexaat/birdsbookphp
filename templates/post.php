<?php  //post.php  ?>

<?php  function renderPost($post){

    $imageName = htmlspecialchars($post['image']);
    $user = htmlspecialchars($post['user_id']);
    $date = htmlspecialchars($post['created']);
    $content = htmlspecialchars($post['content']);
    $src = htmlspecialchars($post['image']);
    $nick_name = htmlspecialchars($post['nick_name']);

    $avatar = "<div class='avatar'>${nick_name[0]}</div>";
    if($post['avatar']){
       $avatar = "
        <div class='avatar
            clickable'
            style='background-image: url(./profiles/{$post['avatar']})'>
            </div>";
    }
    
    echo
    "<div class='post-container'>
        <div class='post-header'>
            <a href='./profile.php?user_id=$user' class='avatar clickable'>$avatar</a>
            <div> $date </div>
        </div>
        <img src='images/$src'>
        <div class='post-content'> $content </div>
    </div>";
    }
?>