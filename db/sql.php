<?php //sql.php ?>


<?php
    function getConnection(){
        $db_hostname = "localhost";
        $db_username = "user";
        $db_password = "password";
        $db_name = "db";
    	$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);
        //check connection
    	if(!$conn){
    		return 'Connection error: '. mysqli_connect_error();
    	}
    	return $conn;
    }
?>
   
<?php

function getUser($nick_name, $password){

    $conn = getConnection();
   
    $nick_name = mysqli_real_escape_string($conn, $nick_name);
    $password = mysqli_real_escape_string($conn, $password);
    
    $sql = "SELECT * FROM users WHERE nick_name = '$nick_name' AND password = '$password' LIMIT 1";
       
    // get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

    // fetch the resulting rows as an array
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // free the $result from memory (good practise)
    mysqli_free_result($result);
    
    if($users && count($users) > 0){
        mysqli_close($conn);
        return $users[0];
    }
    
    mysqli_close($conn);
    
    return false;
}

function savePost($user_id, $content, $fileNameNew){

        $conn = getConnection();
        
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $content = mysqli_real_escape_string($conn, $content);
        $fileNameNew = mysqli_real_escape_string($conn, $fileNameNew);
	        
	    $sql = "INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$fileNameNew')";
            
        if(mysqli_query($conn, $sql)){
            echo "New record created successfully ";
            mysqli_close($conn);
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        mysqli_close($conn);
        return false;
    }

function getPosts(){
    
    $conn = getConnection();
    
    $sql = 'SELECT posts.id, posts.user_id, posts.created, posts.content, posts.image, users.nick_name, users.image as avatar FROM posts JOIN users ON users.id = posts.user_id ORDER BY created DESC';

    // get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

    // fetch the resulting rows as an array
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // free the $result from memory (good practise)
    mysqli_free_result($result);
    
    mysqli_close($conn);
    
    return $posts;
}

function getPostsFilter($filter){
  
    $conn = getConnection();
  
    $where = "";
  
    if($filter != null){
        foreach($filter as $k => $v){
            $where = $where .  " AND $k = '$v'";
        }
         
        if(strlen($where) > 4) {
            $trimmed = substr($where, 4);
            $where = "WHERE ".$trimmed; 
        }
    }
    
    $sql = "SELECT posts.id, posts.user_id, posts.created, posts.content, posts.image, users.nick_name, users.image as avatar FROM posts JOIN users ON users.id = posts.user_id $where ORDER BY created DESC";



    // get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

    // fetch the resulting rows as an array
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // free the $result from memory (good practise)
    mysqli_free_result($result);
    
    mysqli_close($conn);
    
    return $posts; 
}

function saveSessionToDatabase($user_id, $uuid){
    
    $conn = getConnection();    
    
    $uuid =  mysqli_real_escape_string($conn, $uuid);
    $user_id = mysqli_real_escape_string($conn, $user_id);

    $sql = "INSERT INTO sessions (user_id, session) VALUES('$user_id', '$uuid') ON DUPLICATE KEY UPDATE session =  '$uuid'";

    if(mysqli_query($conn, $sql)){
        echo "New record created successfully ";
       	mysqli_close($conn);
       	return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    	        
    // close connection
	mysqli_close($conn);
	
	return false;
     
}

function getUserBySession($session){
    $conn = getConnection();  
    $session =  mysqli_real_escape_string($conn, $session);
    
    //$sql = "SELECT * FROM sessions WHERE session = '$session' LIMIT 1";
    $sql = "SELECT users.id, nick_name, email, about, image FROM users WHERE users.id = (SELECT user_id FROM sessions WHERE session = '$session' LIMIT 1) LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
    
    if(count($users) > 0) {
        return $users[0];
    }
    
    return false;
}

function saveUserToDatabase($nick_name, $email, $password){
    $conn = getConnection();
   
    $nick_name = mysqli_real_escape_string($conn, $nick_name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    $sql = "INSERT INTO users (nick_name, email, password) VALUES ('$nick_name', '$email', '$password')";

    if(mysqli_query($conn, $sql)){
        $last_id = $conn->insert_id;
        mysqli_close($conn);
        return array("last_id"=>$last_id);
    } else {
        $error = array("error" => $conn->error);
        mysqli_close($conn);
        return array("error" => $error);
    }
}

function deleteSessionFromDatabase($session){
    $conn = getConnection();
    $session = mysqli_real_escape_string($conn, $session);
    $sql = "DELETE FROM sessions WHERE session = '$session'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
}

function getUserById($id){
    $conn = getConnection();
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT id, nick_name, email, about, image FROM users WHERE id = '$id' LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    
    if($users && count($users) > 0){
        mysqli_close($conn);
        return $users[0];
    }
    mysqli_close($conn);
    
    return false;
    
}

function updateUserProfile($profile){
    $conn = getConnection();
    $id = mysqli_real_escape_string($conn, $profile[id]);
    $values = "";
    
    foreach($profile as $k => $v){
        if($k!=id){
            $values = $values." $k = '$v', "; 
        }
    }
    
    if(strlen($values) > 0){
        $values =  rtrim($values,", ");
    }
    
    $values = mysqli_real_escape_string($conn, $values);
    
    if($id && $values){
        $sql = " UPDATE users SET $values WHERE id = '$id'";
        if(mysqli_query($conn, $sql)){
            mysqli_close($conn);
            return true;
        } else {
            mysqli_close($conn);
            return $conn->error;
        }
    }
    mysqli_close($conn);
    return false;
}

?>