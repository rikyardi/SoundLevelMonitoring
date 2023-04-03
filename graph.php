<?php 
	header('Content-Type: application/json');
    require_once("config.php");

    $query = mysqli_query($db, "select waktu, data from datalogs ORDER BY id desc LIMIT 50");
    $data = array();
	foreach ($query as $row) {
		$data[] = $row;
	}
	echo json_encode($data);
?>