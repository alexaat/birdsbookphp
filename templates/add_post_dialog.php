<?php
//add_post_dialog.php
  
session_start();
?>

<?php 

    if(isset($_POST['submit'])){
        
        require __DIR__.'/../util/cookie.php';
        $cookie = get_cookie();
        
      
        
        require __DIR__.'/../db/sql.php';
        $user = getUserBySession($cookie);


        if($user) {
            $content = $_POST['content'];
            $images = $_POST['image'];
            
          

            //upload file
            $fileNameNew = uniqid('',true);
            $_FILES["image"]["name"] = $fileNameNew;
            
            require __DIR__.'/../util/images.php';
            $uploadOk =  uploadImages($_FILES);
            
            //save post
            if(!empty($content) && $uploadOk){
                if(savePost($user[id], $content, $fileNameNew)){
                    $_SESSION["modal_display"] = 'none';
                    header("Location: ../index.php");  
                }
            }
        } else {
            delete_cookie();
            session_unset();
            session_destroy();
        }
        
    }
    
?>


    <div class='modal' style='display: <?php  echo $_SESSION["modal_display"] ?> '>
        <div class='modal-content' style='width: 650px;' >
                <div class='dialog-header'>
                    <div class='dialog-title'>New Post</div>
                    <form action='index.php' method='GET' >
                        <input type='submit' value = '' class='close' name='close-dialog-click'/>
                    </form>
                </div>
                <form method='POST' action='templates/add_post_dialog.php' class='new-post-dialog-container' enctype="multipart/form-data">
                    <div class='file-input-container'>
                        <input type='file' name='image' accept='image/*' class='file-input' id='input-file'/>
                    </div>
                    <textarea name='content' placeholder='write you post here...' columns=40 rows=7></textarea>
                    <input type='submit' name='submit' value='POST' formmethod='POST' class='post-button'/>
                </form>
        </div>
    </div>
    
    <script>
        document.querySelector('#input-file').addEventListener('change', (e) => {
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