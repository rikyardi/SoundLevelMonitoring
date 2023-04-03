<?php 
    require_once('config.php');

    date_default_timezone_set("Asia/Jakarta");

    $tanggal =  date("d-m-Y"); 
    $waktu = date("H:i:s"); 

    $data = $_GET['data'];
    // if(isset($_GET['data'])){
    //     $id_sensor = $_GET['sensor'];
    //     $data = $_GET['data'];
    mysqli_query($db, "insert into datalogs values(NULL, '$tanggal', '$waktu', '$data' )");  
    // }
    
?>