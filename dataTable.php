<?php 
    require_once('config.php');    
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>    
            <th scope="col">Tanggal</th>
            <th scope="col">Waktu</th>
            <th scope="col">Data</th>
        </tr>
    </thead>
    <tbody>
        <?php    
        $page = (isset($_POST['page']))? $_POST['page'] : 1;
        $limit = 25; 
        $limit_start = ($page - 1) * $limit;
        $no = $limit_start + 1;

        $query = "SELECT * FROM datalogs ORDER BY id desc LIMIT $limit_start, $limit";
        $dewan1 = $db->prepare($query);
        $dewan1->execute();
        $res1 = $dewan1->get_result();
        
        while ($row = $res1->fetch_assoc()) {
        ?>
        <tr>
            <th scope="col"><?= $no++ ?></th>
            <td><?= $row['tgl'] ?></td>
            <td><?= $row['waktu'] ?></td>
            <td><?= $row['data'] ?> dB</td>
            </tr>
            <?php  } ?>
    </tbody>
</table>
        <nav class="mb-5">
          <ul class="pagination justify-content-end">
            <?php
               $query_jumlah = "SELECT count(*) AS jumlah FROM datalogs";
               $dewan1 = $db->prepare($query_jumlah);
               $dewan1->execute();
               $res1 = $dewan1->get_result();
               $row = $res1->fetch_assoc();
               $total_records = $row['jumlah'];

                $jumlah_page = ceil($total_records / $limit);
                $jumlah_number = 1; //jumlah halaman ke kanan dan kiri dari halaman yang aktif
                $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
                $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;
             
                     if($page == 1){
                       echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
                       echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
                     } else {
                       $link_prev = ($page > 1)? $page - 1 : 1;
                       echo '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
                       echo '<li class="page-item halaman" id="'.$link_prev.'"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
                     }
        
                     for($i = $start_number; $i <= $end_number; $i++){
                       $link_active = ($page == $i)? ' active' : '';
                       echo '<li class="page-item halaman '.$link_active.'" id="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
                     }
        
                     if($page == $jumlah_page){
                       echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
                       echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
                     } else {
                       $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
                       echo '<li class="page-item halaman" id="'.$link_next.'"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
                       echo '<li class="page-item halaman" id="'.$jumlah_page.'"><a class="page-link" href="#">Last</a></li>';
                     }
                   ?>
                 </ul>
               </nav>