<?php

function delete($path){
    if (file_exists($path)) {
        unlink($path);
    } 
}

?>