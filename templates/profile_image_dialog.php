<?php
//profile_image_dialog.php

session_start();
?>


<?php

    if(isset($_POST['submit'])){
        require __DIR__.'/../util/cookie.php';
        $cookie = get_cookie();
        
        require __DIR__.'/../db/sql.php';
        $user = getUserBySession($cookie);
        
        if($user) {
            $images = $_POST['image'];
            
            require __DIR__.'/../util/uuid.php';
            $fileNameNew = getUUID();
            $_FILES["image"]["name"] = $fileNameNew;
            
            require __DIR__.'/../util/images.php';
            $target_dir = "../profiles/";
            $uploadOk =  uploadImages($_FILES, $target_dir);
            
            //Update db
            if($uploadOk){
                $uploadOk=updateUserProfile([id => $user['id'], image => $fileNameNew]);
            }
            if($uploadOk){
                $current_user = $_SESSION["current_user"];
                $oldFileName = $current_user['image'];
                $current_user['image'] = $fileNameNew;
                $_SESSION["current_user"] = $current_user;
                if($oldFileName){
                    require __DIR__.'/../util/files.php';
                    delete("../profiles/$oldFileName");   
                } 
            }
            
        }
        $_SESSION["modal_display_profile_image"] = 'none';
        header("Location: ../profile.php?user_id={$user['id']}");
        
    }


?>

   <div class='modal' style='display: <?php  echo $_SESSION["modal_display_profile_image"] ?> '>
        
        <div class='modal-content' style='width: 650px;' >
                <div class='dialog-header'>
                    <div class='dialog-title'>Profile Image</div>
                    <form action='profile.php' method='GET' >
                        <input type='submit' value = '' class='close' name='close-dialog-click'/>
                    </form>
                </div>
                <form method='POST' action='templates/profile_image_dialog.php' class='dialog-image-container' enctype="multipart/form-data">
                    <div class='file-input-container' style="background-image: url('./icons/profile.png');">
                        <input type='file' name='image' accept='image/*' class='file-input' id='input-file-profile-image'/>
                    </div>
                    <input type='submit' name='submit' value='SAVE' formmethod='POST' class='post-button'/>
                </form>
        </div>
    </div>
    
    <script>
        document.querySelector('#input-file-profile-image').addEventListener('change', (e) => {
                const file = e.target.files[0];
                const fileReader = new FileReader();
                fileReader.readAsDataURL(file); 
                fileReader.onload = function (){
                    const fileInputContainer = document.querySelector('.file-input-container');
                    fileInputContainer.setAttribute('style',                `
                    background-image: url('${fileReader.result}');
                    background-size: contain;
                    `);
                }
            });
    
    </script>