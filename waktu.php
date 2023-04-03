<?php 
    require_once('config.php');

    $sql = "SELECT * FROM datalogs ORDER BY id DESC LIMIT 1";
    $query = mysqli_query($db, $sql);
    $data = mysqli_fetch_array($query);

    echo "<b>".$data['waktu']."</b><br>";

    $mydate = strtotime($data['tgl']);
    echo date("l", $mydate).", ".date('jS F Y', $mydate);
?>