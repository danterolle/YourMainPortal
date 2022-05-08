<!DOCTYPE html>
<html>
<?php 
session_start();
require 'head.html';

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

?>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

  <?php require 'navigation.php'; ?>
  <!-- Page Content -->
  <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Choose your card</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" >
              <form onsubmit='return joinMeet(this)'>
            <div id="cardTab-cont" class="table-responsive"></div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button id="sendButt"  type="submit" class="btn btn-primary">Salva</button>
            </div>
            </form>
          </div>
        </div>
      </div>

  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Your Invites
        </div>

        <div class="card-body">
          <div id="inviteCard-cont" class="card-deck" ></div>
        </div>

      </div>
    </div>
  </div>
</body>

</html>


<script>
  $.ready(fetchCardTab());

  function fetchCardTab() {
    ret = false;
    $.ajax({
      url: "card-info-fetch.php",
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
          CreateTableFromJSON(data, "#cardTab-cont");
          ret = true;
        }
      }
    });
    return ret;
  }
  $.ready(fetchInviteCards());

  function fetchInviteCards(dataIn) {
    ret = false;
    $.ajax({
      url: "invite-info-fetch.php",
      type: 'GET',
      data: dataIn,
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
          //CreateTableFromJSON(data, "#partTab-cont");
          createInviteCards(data, "#inviteCard-cont");
          ret = true;
        }
      }
    });
    return ret;
  }

  function openModal(b){
    meet_id = $(b).closest(".invite-card").data("meetid");  
    $("#cardModal").find("form").data("meet_id", meet_id)
    console.log($("#cardModal").find("#sendButt").data())
    $("#cardModal").modal('toggle');
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
    //additional header
    var th = document.createElement("th");
    th.innerHTML = "Modifica";
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
      createCell(table.rows[i].insertCell(table.rows[i].cells.length), "", 'col', insertIn);
    }
    $(table).prepend(header);
    //table created
    divContainer.append(table);
    //Add Bootstrap
    $(insertIn).find("table").addClass("table table-striped table-bordered");
    //DataTable
    $('#cardTab-cont tr > td:nth-child(1), #cardTab-cont tr > th:nth-child(1)').hide();
    $('#cardTab-cont tr > td:nth-child(2), #cardTab-cont tr > th:nth-child(2)').hide();
    $('#cardTab-cont tr > td:nth-child(5), #cardTab-cont tr > th:nth-child(5)').hide();
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
      // "columnDefs": [
      //     {"title": "meeting_id", "targets": 0, "visible": true, "searchable": false},
      //     {"title": "creator", "targets": 1, "visible": true, "searchable": false},
      //     {"title": "Partecipanti", "targets": 2, "visible": true, "searchable": true},
      //     {"title": "Luogo", "targets": 3, "visible": true, "searchable": true},
      //     {"title": "Nome Meet", "targets": 4, "visible": true, "searchable": true},
      //     {"title": "Tuo Rating", "targets": 5, "visible": true, "searchable": true},
      //     {"title": "Ruolo", "targets":6, "visible": true, "searchable": true}
      //     ]
    });
    $("#cardTab-cont").find("[type='radio']").attr('name', "card_id");
    $("#userTab-cont").find("[type='checkbox']").attr('name', "user_id[]");
  }

  function createCell(cell, text, style, insertIn) {
    if (insertIn == "#userTab-cont") {
      //Checkbox Button
      var row = $(cell).closest("tr"); // Find the row
      var cells = row.find("td");
      rad = $('<input type="checkbox"></input>').val(cells[0].innerText);
      $(cell).append(rad);
    } else {
      //Radio Button
      var row = $(cell).closest("tr"); // Find the row
      var cells = row.find("td");
      rad = $('<input type="radio" required></input>').val(cells[0].innerText);
      $(cell).append(rad);
    }
  }

  function createInviteCards(json, insertIn) {
    par = $(insertIn).parent();
    $(insertIn).empty();
    $.each(data, function(i, item) {
      //console.log($(insertIn))
      txt = '<div id=card'+ item.meet_id +' data-meetid="' + item.meet_id + '" class="card mb-3 invite-card" style="max-width: 18rem;">  <h5 class="card-header">' + item.title + '</h5>  <div class="card-body ">    <h5 class="card-title">' + item.place + '</h5>    <p class="card-text">' + item.date + ' ' + item.time + '</p>  </div><div class="card-footer"></div></div>'
      card = $(insertIn).append(txt)
      
      console.log(item.reply)
      if(item.reply==1){
        //Joined
        $("#card"+ item.meet_id).addClass("border-success").find(".card-body").addClass("text-success")
        $("#card"+ item.meet_id).find(".card-header").append("<button style='float:right;' onclick='openModal(this)' data-target='#cardModal' class='join btn btn-outline-success btn-sm' disabled> <i class='fa fa-check-circle'></i> Joined</button>");
        $("#card"+ item.meet_id).find(".card-footer").append("<button onclick='openModal(this)' data-target='#cardModal' class='join btn btn-outline-success btn-sm'>Change Card</button>");
        $("#card"+ item.meet_id).find(".card-footer").append("<button style='float:right;' onclick='refuseMeet(this)' class='refuse btn btn-outline-danger btn-sm'>Leave</button>");
      }else if(item.reply==2){
        //Refused
        $("#card"+ item.meet_id).find(".card-header").append("<button style='float:right;' onclick='openModal(this)' data-target='#cardModal' class='join btn btn-outline-danger btn-sm' disabled> <i class='fa fa-times-circle'></i> Refused</button>");
        $("#card"+ item.meet_id).addClass("border-danger").find(".card-body").addClass("text-danger")
        $("#card"+ item.meet_id).find(".card-footer").append("<button onclick='openModal(this)' data-target='#cardModal' class='join btn btn-outline-success btn-sm'>Join</button>");

      }else{
        //To Answer
        $("#card"+ item.meet_id).addClass("border-primary").find(".card-body").addClass("text-primary")
        $("#card"+ item.meet_id).find(".card-footer").append("<button onclick='openModal(this)' data-target='#cardModal' class='join btn btn-outline-success btn-sm'>Join</button>");
        $("#card"+ item.meet_id).find(".card-footer").append("<button style='float:right;' onclick='refuseMeet(this)' class='refuse btn btn-outline-danger btn-sm'>Refuse</button>");
      }
    })
  }

  function refuseMeet(b) {
    meet_id = $(b).closest(".invite-card").data("meetid");
    sendPost(b, "invite-info-update.php", [{
      name: "meet_id",
      value: meet_id
    }, {
      name: "reply",
      value: 2
    }], fetchInviteCards);

    sendPost(b, "part-info-remove.php", [{
      name: "meet_id",
      value: meet_id
    }], fetchInviteCards);

  }

  function joinMeet(b) {
    meet_id = $(b).data("meet_id");
  console.log(meet_id)
    sendPost(b, "invite-info-update.php", [{
      name: "meet_id",
      value: meet_id
    }, {
      name: "reply",
      value: 1
    }], fetchInviteCards);
    sendPost(b, "part-info-add.php", [{
      name: "meet_id",
      value: meet_id
    }], fetchInviteCards);

    return false;
  }

  function sendPost(b, loc, addData = null, callback = null,closeModal=true) {
    sdata = $(b).serializeArray();
    if (addData != null) {
      $.each(addData, function(i, d) {
        sdata.push(d);
      });
    }
    $.ajax({
      url: loc,
      type: 'POST',
      data: sdata,
      async: true,
      cache: false,
      timeout: 30000,
      dataType: "json",
      error: function() {
        return false;
      },
      success: function(res) {
        console.log(res);
        //res = JSON.parse(resp);
        if (res.res == "false") {
          return false;
        } else if (res.res == "true") {
          if (typeof callback === 'function') {
            callback(res);
            if(closeModal){
                            $(b).closest(".modal").modal('toggle');
                          }
          }
          return false;
        }
      }
    });
    return false;
  }
  
</script>