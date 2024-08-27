<?php
    //uuid.php
    function getUUID(){
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
    }
?>