<?php 
    require_once("config.php");

    $tgl = $_POST['tgl'];
    $query = mysqli_query($db, "select waktu, data from datalogs where tgl='$tgl' ORDER BY id desc");

    $data = array();    
    foreach ($query as $row) {
		$data[] = $row;
	}
    echo json_encode($data);

?>