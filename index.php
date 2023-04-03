<?php 
    require_once('config.php');
    include('navbar.php');
?>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="mt-5">Sound Sensor Monitoring</h1> 
                <hr class="w-50">
                <div class="row border-bottom">
                    <div class="col">
                        <h3 class="mt-3" id="waktu"></h3>
                    </div>
                    <div class="col text-end">
                            <h3 class="mt-3">Kebisingan </h3>
                            <h3 class="mt-3" id="data"></h3>
                    </div>
                </div>
                <div class="row mt-3 rounded" >
                    <div class="col-md-12">
                        <canvas id="myChart" class="bg-light m-3 rounded" ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col">
                <hr>
                <h2 class="mt-5">Datalog</h2>
                <hr class="w-50">
                <form action="findDatalog.php" method="post">
                <select name="tgl" onfocus="this.size=5;" onblur="this.size=1;" onchange="this.size=1; this.blur();" class="form-select form-select mb-3 w-25" aria-label=".form-select-lg example">
                    <?php
                      $query = mysqli_query($db, "select distinct tgl from datalogs");
                      while($data = mysqli_fetch_array($query)){
                    ?>
                    <option value="<?= $data[0] ?>"><?=$data[0]?></option>
                    <?php } ?>
                  </select>
                  <input type="submit" value="Cari" class="btn btn-primary mt-3">
                </form>
                <div class="table-responsive" id="dataTable"></div>
                
          </div>
        </div>
      </div> 
  </body>
</html>

<script>
  var ctx = document.getElementById("myChart");
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: 'Decibel',
        data: [],
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
  var updateChart = function() {
    $.ajax({
      url: "graph.php",
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        var labels = [];
        var datas = [];
        for (var i in data) {
            labels.push(data[i].waktu);
            datas.push(data[i].data);
        }
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = datas;
        myChart.update();
        // $('#bgkondisi').html("ON");
        // $('#bgkondisi').css('background-color', 'green');
        $('#data').html(datas[0]+" dB");
      },
      error: function(data){
        console.log(data);
      }
    });
    }
    
    var today = function(){
    $.ajax({
        url: "waktu.php",
        type: 'GET',
        dataType: 'html',
        success:function(data){
            $('#waktu').html(data);
        }
    })
    }
    
    $(document).ready(function(){
        load_data();
        function load_data(page){
            $.ajax({
                    url:"dataTable.php",
                    method:"POST",
                    data:{page:page},
                    success:function(data){
                        $('#dataTable').html(data);
                        console.log(data);
                    }
            })
        }
        $(document).on('click', '.halaman', function(){
            var page = $(this).attr("id");
            load_data(page);
        });
    });

  updateChart();
  setInterval(() => {
    updateChart();
    today();
  }, 1000);

</script>
   