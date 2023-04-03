<?php 
    require_once('config.php');
    include('navbar.php');
   
    $data = array();
    $waktu = array();
    $tgl = $_POST['tgl'];
    $nilai = array();
    $query = mysqli_query($db, "select waktu, data from datalogs where tgl='$tgl'");
    foreach ($query as $row) {
        $data[] = $row['data'];
        $waktu[] = $row['waktu'];
        $nilai[] = intval(($row['data']));
    }
    $jumlahData = count($nilai);
    $sumData = array_sum($nilai);
    $max = max($nilai);
    $min = min($nilai);
    $rata2 = $sumData/$jumlahData;

    $first = $waktu[0];
    $last = end($waktu);

    $mydate = strtotime($_POST['tgl']);
    $tanggal =  date("l", $mydate).", ".date('jS F Y', $mydate);

?>
<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="mt-5">Cari Datalog</h2>
            <h4>Data <?= $tanggal ?></h4>
            <hr>
            <div class="row">
              <div class="col">
                <h5 class="p-1">Kebisingan Tertinggi : <span class="p-1 rounded" id="max"><?= $max?> dB</span></h5>
                <h5 class="p-1">Kebisingan Terendah  : <span class="p-1 rounded" id="min"><?= $min?> dB</span></h5>
              </div>
              <div class="col">
                <h5 class="p-1">Rata-rata Kebisingan : <span class="p-1 rounded" id="avg"><?= round($rata2) ?> dB</span></h5>
                <h5 class="p-1">Durasi : <span class="p-1 "><?= $first." - ".$last; ?></span></h5>
              </div>
              <hr>
            </div>
            <div class="row mt-3 rounded" >
                    <div class="col-md-12">
                        <canvas id="myChart" class="bg-light m-3 rounded" ></canvas>
                    </div>
            </div>
            
        </div>
    </div>
</div>      


<script>
if(<?= $max ?> < 55){
  $('#max').css('background-color', 'lightgreen');
}else if(<?= $max ?> >= 55 && <?= $max ?> <= 70){
  $('#max').css('background-color', 'yellow');
}
else{
  $('#max').css('background-color', 'red');
}

if(<?= $min ?> < 55){
  $('#min').css('background-color', 'lightgreen');
}else if(<?= $min ?> >= 55 && <?= $min ?> <= 70){
  $('#min').css('background-color', 'yellow');
}
else{
  $('#min').css('background-color', 'red');
}

if(<?= $rata2 ?> < 55){
  $('#avg').css('background-color', 'lightgreen');
}else if(<?= $rata2 ?> >= 55 && <?= $rata2 ?> <= 70){
  $('#avg').css('background-color', 'yellow');
}
else{
  $('#avg').css('background-color', 'red');
}

var tgl = <?= json_encode($tgl) ?>;
var datas = <?= json_encode($data); ?>;
var labels = <?= json_encode($waktu); ?>;

  var ctx = document.getElementById("myChart");
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Decibel',
        data: datas,
        backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                      ],
                      borderColor: [
                      'rgba(255,99,132,1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                      ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        xAxes: [],
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    }
  });

</script>