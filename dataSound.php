<?php 
    require_once("config.php");

    $query = mysqli_query($db, "select * from datalog ORDER BY id_datalog DESC LIMIT 1");

    $data = mysqli_fetch_array($query);
    echo $data['data']." dB";
?>