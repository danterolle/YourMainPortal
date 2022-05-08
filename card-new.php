<!DOCTYPE html>
<html>
<?php
session_start();
require 'head.html';

if (!isset($_SESSION["user_id"])) {
  die("No Login! <a href='home.php'> Log here! </a>");
}
if (isset($_GET["u"]) && $_GET["u"] == "true") {
    include "db.php";
    $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
    $card_id = mysqli_real_escape_string($conn, $_GET["id"]);

    $query = "SELECT * FROM mycard.cards WHERE user_id = '$user_id' AND card_id = '" . $card_id . "'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo ('<div class="error">' . mysqli_error($conn) . '<div>');
    }

    if ($result) {
        $arr = mysqli_fetch_array($result, MYSQLI_ASSOC);

    } else {}
} else { $_GET["u"] = false;}
?>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

<?php require 'navigation.php'; ?>
  <!-- Page Content -->
  <div class="content-wrapper">
    <form  <?php if ($_GET["u"] == true) {echo ('onsubmit=\' return sendPost(this,"card-info-update.php",null, null)\'');} else {echo ('onsubmit= \' return sendPost(this, "card-info-add.php",null, null)\'');}?>>
    <div class="container-fluid">
    <div class="row">
    <div class="col" >
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-bars"></i> Info Card
        </div>
        <div class="card-body">
<div class="container-fluid">
        <input class="form-control" type="text" name="title" placeholder="Title" value="<?php echo ((isset($arr["title"]) == true) ? $arr["title"] : ''); ?>" required>
        <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo ((isset($arr["name"]) == true) ? $arr["name"] : ''); ?>" autocomplete="given-name" required>
        <input class="form-control" type="text" name="surname" placeholder="Surname" value="<?php echo ((isset($arr["surname"]) == true) ? $arr["surname"] : ''); ?>" required>
        <input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo ((isset($arr["email"]) == true) ? $arr["email"] : ''); ?>" required>
        <input  type="file" name="fileToUpload" placeholder="Logo">
        </div>
        </div>
        </div>
        </div>

    <div class="col">
          <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-graduation-cap"></i> Select Education Experience
        </div>
        <div class="card-body">
          <div id="edu-tab-cont" class="table-responsive"></div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Select Education Experience
        </div>
        <div class="card-body">
          <div id="work-tab-cont" class="table-responsive"></div>
        </div>
      </div>
      </div>
      </div>
        <button type="submit" class="btn btn-primary">Salva Carta</button>

    </form>
    </div>


    </div>


</body>

<script>
function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

$(document).ready(fetchEduTab);
$(document).ready(fetchWorkTab);
function fetchEduTab() {
    $.ajax({
        url: "edu-info-fetch.php",
        type: 'GET',
        async: true,
        cache: false,
        timeout: 30000,
        dataType:"json",
        error: function () {
            return false;
        },
        success: function (res) {
            console.log(res);
            //res = JSON.parse(res);
            if (res.res == "false") {
                return false;
            } else if (res.res == "true") {
                data = res.data
                CreateTableFromJSON(data, "#edu-tab-cont");
            }

        }
    });
    // return res;
}

function fetchWorkTab() {
    ret=false;
    $.ajax({
        url: "work-info-fetch.php",
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
            //res = JSON.parse(resp);
            if (res.res == "false") {
                ret = false;
            } else if (res.res == "true") {
                data = res.data
                CreateTableFromJSON(data, "#work-tab-cont");
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
    var th = document.createElement("th"); // TABLE HEADER.
        th.innerHTML = "Seleziona";
        thr.appendChild(th);
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

    //add control cell
    for (i = 0; i < table.rows.length; i++) {
        createCell(table.rows[i].insertCell(table.rows[i].cells.length), "", 'col',insertIn);
    }
    $(table).prepend(header);
    //table created
    divContainer.append(table);

    //Add Bootstrap
    $(insertIn).find("table").addClass("table table-striped table-bordered");
    //DataTable
    switch (insertIn) {
        case '#edu-tab-cont':

             $('#edu-tab-cont tr > td:nth-child(5), #edu-tab-cont tr > th:nth-child(5)').hide();
             $('#edu-tab-cont tr > td:nth-child(1), #edu-tab-cont tr > th:nth-child(1)').hide();

            $("#edu-tab-cont").find("[type='radio']").attr('name', "edu_id");

            $(insertIn).find("table").DataTable({
                "paging": false,
                "info": false,
                "columnDefs": [
                    {"title": "ex_id", "targets": 0, "visible": true, "searchable": false},
                    {"title": "Titolo di Studio", "targets": 1, "visible": true, "searchable": true},
                    {"title": "Anno", "targets": 2, "visible": true, "searchable": true},
                    {"title": "Luogo", "targets": 3, "visible": true, "searchable": true},
                    {"title": "user_id", "targets": 4, "visible": true, "searchable": false},
                    {"title": "Seleziona", "targets": 5, "visible": true, "searchable": false}
                    ]
            });
            break;

        case '#work-tab-cont':

            $('#work-tab-cont tr > td:nth-child(2), #work-tab-cont tr > th:nth-child(2)').hide();
            $('#work-tab-cont tr > td:nth-child(1), #work-tab-cont tr > th:nth-child(1)').hide();

            $("#work-tab-cont").find("[type='radio']").attr('name', "work_id");

        $(insertIn).find("table").DataTable({
                "paging": false,
                "info": false,
                "columnDefs": [
                    {"title": "c_id", "targets": 0, "visible": true, "searchable": false},
                    {"title": "w_id", "targets": 1, "visible": true, "searchable": false},
                    {"title": "Compagnia", "targets": 2, "visible": true, "searchable": true},
                    {"title": "Ruolo", "targets": 3, "visible": true, "searchable": true},
                    {"title": "Anno", "targets": 4, "visible": true, "searchable": true},
                    {"title": "Luogo", "targets": 5, "visible": true, "searchable": true},
                    {"title": "Seleziona", "targets": 6, "visible": true, "searchable": false}
                    ]

            });
            break;
    }


}

function createCell(cell, text, style, insertIn) {
    //Radio Button
    var row = $(cell).closest("tr"); // Find the row
    var cells = row.find("td");
    rad=$('<input type="radio" required></input>').val(cells[0].innerText);


    $(cell).append(rad);

    if(insertIn=="#edu-tab-cont"){
    if(cells[0].innerText == ("<?php echo ((isset($arr["education_experience_id"]) == true) ? $arr["education_experience_id"] : ''); ?>")){
        rad.prop('checked', true);}
    }else{
        if(cells[0].innerText == ("<?php echo ((isset($arr["work_experience_id"]) == true) ? $arr["work_experience_id"] : ''); ?>")){
        rad.prop('checked', true);}

    }
}

function sendPost(b, loc, addData = null, callback = null, closeModal = true) {
    var form = $(b);
  var formdata = false;
  if (window.FormData) {
    formdata = new FormData(form[0]);
    console.log(formdata)
    <?php if ($_GET["u"] == true) {echo ('formdata.append("card_id", '.$card_id.');');} else {echo ('');}?>
  }
  var formAction = form.attr('');
     $.ajax({
        url: loc,
        type: 'POST',
        data: formdata ? formdata : form.serialize(),
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 30000,
        dataType:"json",
        error: function () {
            return false;
        },
        success: function (res) {
            console.log(res);
            //res = JSON.parse(resp);
            if (res.res == "false") {
                return false;
            } else if (res.res == "true") {
        if (typeof callback === 'function') {
          callback(res);}
          if (closeModal) {
            $(b).closest(".modal").modal('toggle');
            window.location.href = "cards.php";
          }

                return false;
            }
        }
    });
return false;
}


</script>
