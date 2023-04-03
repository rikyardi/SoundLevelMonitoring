<?php

    $db = mysqli_connect('localhost', 'root', '', 'sound_monitor');
    if ($db->connect_error) {
        die("Connection Failed: ". $db->connect_error);
    }
    
    return;
?>