<!DOCTYPE html>
<html>
<?php 
session_start();
require 'head.html';

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

?>

<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <?php require 'navigation.php'; ?>
  <!-- Page Content --> 
  <div class="content-wrapper"> 
  
  <!-- Buttons  -->
  <div id="gifButtonsView" class="container">
    </div>
     <!-- Main Container  -->
    <div class="container">
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
            <!-- Gifs  -->
            <div id="gifsView"></div>
        </div>
         <!-- Action Submission Form  -->
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <form id="gif-form" role="form">
                <h2>Aggiungi una categoria:</h2>
                <div class="form-group">
                    <label for="action-input"></label>
                    <input type="text" id="action-input">
                </div>
                <input id="addGif" type="submit" value="Aggiungi una categoria" class="btn btn-primary">
                <input id="removeGif" type="submit" value="Rimuovi le categorie inserite" class="btn btn-primary">
            </form>    
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/javascript/javascript.js"></script>
  
  
    <div class="container-fluid">
      <!--
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Meetings
        </div>
        <div class="card-body">
          <div id="meetTab-cont" class="table-responsive">

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Your meetings rating
            </div>
            <div class="card-body">
            <canvas id="myChart" max-width="300px" max-height="300px"></canvas>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Last Meeting Result
            </div>
            <div class="card-body">
            <canvas id="myChartPie" max-width="300px" max-height="300px"></canvas>
            </div>

          </div>
        </div>
        <div class="col">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-calendar"></i> Calendar
            </div>
            <div class="card-body">
            <div id="my-calendar"></div>
            </div>

          </div>
        </div>
      </div> -->
    </div> 
    <!-- /#page-wrapper -->

</body>

<script>
$.ready(fetchDashTab());

function fetchDashTab() {
  ret = false;
  $.ajax({
    url: "dash-meet-info-fetch.php",
    type: 'GET',
    async: true,
    cache: false,
    dataType: "json",
    timeout: 30000,
    error: function() {
      return false;
    },
    success: function(res) {
      console.log(res);
      //res = JSON.parse(resp);
      if (res.res == "false") {
        ret = false;
      } else if (res.res == "true") {
        data = res.data
        CreateTableFromJSON(data, "#meetTab-cont");
        ret = true;
      }
    }
  });
  return ret;
}

function CreateTableFromJSON(json, insertIn) {
  // EXTRACT VALUE FOR HTML HEADER.
  var col = [];
  for (var i = 0; i < json.length; i++) {
    for (var key in json[i]) {
      if (col.indexOf(key) === -1) {
        col.push(key);
      }
    }
  }
  var table = document.createElement("table");
  // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.
  var header = document.createElement("thead");
  var thr = header.insertRow();
  for (var i = 0; i < col.length; i++) {
    var th = document.createElement("th"); // TABLE HEADER.
    th.innerHTML = col[i];
    thr.appendChild(th);
  }
  // ADD JSON DATA TO THE TABLE AS ROWS.
  for (var i = 0; i < json.length; i++) {
    tr = table.insertRow(-1);
    for (var j = 0; j < col.length; j++) {
      var tabCell = tr.insertCell();
      tabCell.innerHTML = json[i][col[j]];
    }
  }
  // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
  var divContainer = $(insertIn);
  divContainer.empty();
  $(table).prepend(header);
  //table created
  divContainer.append(table);
  //Add Bootstrap
  $(insertIn).find("table").addClass("table table-striped table-bordered");
  //DataTable
  $('#meetTab-cont tr > td:nth-child(1), #meetTab-cont tr > th:nth-child(1)').hide();
  $('#meetTab-cont tr > td:nth-child(2), #meetTab-cont tr > th:nth-child(2)').hide();
  $(insertIn).find("table").DataTable({
    "paging": false,
    "info": false,
    "responsive": true,
    //           "columns":[
    //             { "data": 0 },
    //             { "data": 1 },
    //   { "data": 6 },
    //   { "data": 4 },
    //   { "data": 3 },
    //   { "data": 2 },
    //   { "data": 5 }
    // ],
    "columnDefs": [{
        "title": "meeting_id",
        "targets": 0,
        "visible": true,
        "searchable": false
      },
      {
        "title": "creator",
        "targets": 1,
        "visible": true,
        "searchable": false
      },
      {
        "title": "Partecipanti",
        "targets": 2,
        "visible": true,
        "searchable": true
      },
      {
        "title": "Luogo",
        "targets": 3,
        "visible": true,
        "searchable": true
      },
      {
        "title": "Nome Meet",
        "targets": 4,
        "visible": true,
        "searchable": true
      },
      {
        "title": "Tuo Rating",
        "targets": 5,
        "visible": true,
        "searchable": true,
        "render": function(data, type, row) {
          a = '<select id="usefull" name="usefull" data-current-rating=' + data + '>  <option value=""></option>   <option value="1">1</option>    <option value="2">2</option>    <option value="3">3</option>   <option value="4">4</option>                      <option value="5">5</option>                    </select>'
          return a
        }
      },
      {
        "title": "Ruolo",
        "targets": 6,
        "visible": true,
        "searchable": true
      }
    ]
  });
  $("select").each(function() {
    console.log(this)
    $(this).barrating({
      theme: 'fontawesome-stars-o',
      initialRating: $(this).data("currentRating"),
      readonly: true
    });
  })
  $("table").css("width", "100%");
}
$(document).ready(function() {
  $("#my-calendar").zabuto_calendar({
    language: "it",
    ajax: {
      url: "widget-calendar-fetch.php"
    },
    nav_icon: {
      prev: '<i class="fa fa-chevron-left"></i> ',
      next: '<i class="fa fa-chevron-right"></i> '
    },
    cell_border: true,
    today: true,

  });
});
$(document).ready(function() {
    ret=false;
    $.ajax({
        url: "widget-bar-fetch.php",
        type: 'GET',
        async: true,
        cache: false,
        dataType:"json",
        timeout: 30000,
        error: function () {
            return false;
        },
        success: function (res) {
            console.log(res);
            drawChart(res);
        }
    });
    return ret;
});
$(document).ready(function() {
    ret=false;
    $.ajax({
        url: "widget-pie-fetch.php",
        type: 'GET',
        async: true,
        cache: false,
        dataType:"json",
        timeout: 30000,
        error: function () {
            return false;
        },
        success: function (res) {
            console.log(res);
            drawChartPie(res);
        }
    });
    return ret;
});

function drawChart(res) {
  var ctx = document.getElementById("myChart").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: res.label,
      datasets: res.data
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            suggestedMax: 5
          }
        }]
      }
    }
  });
}

function drawChartPie(res) {
  var ctx = document.getElementById("myChartPie").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: res.label,
      datasets: res.data
    }
  });
}

</script>
