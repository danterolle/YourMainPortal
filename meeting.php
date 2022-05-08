<!DOCTYPE html>
<html>
<?php
session_start();
require 'head.html';

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

?>
  <style>
  .pac-container {
    z-index: 9999;
     !important
  }

  </style>

  <body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <?php require 'navigation.php';?>

    <!-- Modal -->
    <div class="modal fade" id="meetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Info Meeting</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <input type="text" name="title" placeholder="Nome Meeting" required>
              <input type="date" name="date" placeholder="Date" required>
              <input type="time" name="time" placeholder="Time" required>
              <input id="pac-input" name="place" type="text" class="controls form-control" placeholder="Address" required>
              <div id="mappe" class="container-fluid" style="height: 400px; width: 100%;"></div>
              <div id="invitedTab-cont" class="table-responsive"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" >Salva</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Invite People</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form onsubmit='return sendPost(this, "invite-info-add.php");'>
              <div id="toInviteTab-cont" class="table-responsive"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Invite</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Page Content -->
    <div class="content-wrapper">
      <div class="container-fluid">

        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-table"></i> Meetings
            <a href="meeting-new.php"><button type="button" style="float:right;" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Create Meeting</button></a>
          </div>
          <div class="card-body">
            <div id="meetTab-cont" class="table-responsive"></div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8">

            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> Partecipants
              </div>
              <div class="card-body">
                <div id="coverflow">
                  <ul>
                  </ul>
                </div>
                <div id="partTab-cont" class="table-responsive"></div>
              </div>
            </div>
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> Meeting Review
              </div>
              <div class="card-body">
                <div id="meetRev-cont">
                  <form class="autoupdate">
                    <textarea name="note"> </textarea>
                    <!-- <input type="text" name="note"></input> -->
                    <select id="usefull" name="usefull">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <select id="importance" name="importance">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> Partecipant Info
              </div>
              <div class="card-body">
                <div id="partRev-cont">
                  <p id="name"></p>
                  <p id="surname"></p>
                  <form class="autoupdate">
                    <input id="card_id" name="card_id" style="display:none"></input>
                    <textarea name="note"> </textarea>
                    <!-- <input type="text" name="note"></input> -->
                    <select id="professionality" name="professionality">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <select id="impression" name="impression">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <select id="aviability" name="aviability">
                      <option value=""></option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- /#page-wrapper -->

  </body>

  <script>
  $.ready(fetchMeetTab());

  function fetchMeetTab() {
    ret = false;
    $.ajax({
      url: "meet-info-fetch.php",
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

  function fetchPartTab(dataIn) {
    ret = false;
    $.ajax({
      url: "part-info-fetch.php",
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
          createCardCoverflow(data, "#coverflow");
          ret = true;
        }
      }
    });
    return ret;
  }

  function fetchInvitedTab(dataIn) {
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
          CreateTableFromJSON(data, "#invitedTab-cont");
          ret = true;
        }
      }
    });
    return ret;
  }

  function fetchPartRevInfo(current, previous) {
    card_id = $(current).find(".card").attr("data-card_id");
    datatab = $("#meetTab-cont").find("table").DataTable();
    data = datatab.row('.selected').data();
    meet_id = data[1];
    ret = false;
    $.ajax({
      url: "wallet-info-fetch-one.php",
      type: 'GET',
      data: {
        "card_id": card_id,
        "meet_id": meet_id
      },
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
          createPartRev(data, "#partRev-cont");
          ret = true;
        }
      }
    });
    return ret;
  }

  function fetchMeetRevTab(dataIn) {
    ret = false;
    $.ajax({
      url: "meet-info-fetch-one.php",
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
          createMeetRev(data, "#meetRev-cont");
          ret = true;
        }
      }
    });
    return ret;
  }

  function fetchToInviteTab(dataIn) {
    ret = false;
    $.ajax({
      url: "user-info-fetch.php",
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
          CreateTableFromJSON(data, "#toInviteTab-cont");
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
    //Custom Content in head
    if (insertIn == "#meetTab-cont") {
      var th = document.createElement("th"); // TABLE HEADER.
      th.innerHTML = "Opzioni";
      thr.appendChild(th);
    }else if (insertIn == "#toInviteTab-cont") {
      var th = document.createElement("th"); // TABLE HEADER.
      th.innerHTML = "Invita";
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
    //Custom Content in cell
    if (insertIn == "#meetTab-cont") {
      for (i = 0; i < table.rows.length; i++) {
        createCell(table.rows[i].insertCell(table.rows[i].cells.length), insertIn);
      }
    } else if (insertIn == "#toInviteTab-cont") {
      for (i = 0; i < table.rows.length; i++) {
      createCell(table.rows[i].insertCell(table.rows[i].cells.length), insertIn);
    }
    }
    $(table).prepend(header);
    //table created
    divContainer.append(table);
    //Add Bootstrap
    $(insertIn).find("table").addClass("table table-striped table-bordered");
    //DataTable
      $('#meetTab-cont tr > td:nth-child(1), #meetTab-cont tr > th:nth-child(1)').hide();
      $('#meetTab-cont tr > td:nth-child(2), #meetTab-cont tr > th:nth-child(2)').hide();
      $('#meetTab-cont tr > td:nth-child(8), #meetTab-cont tr > th:nth-child(8)').hide();
      $('#meetTab-cont tr > td:nth-child(9), #meetTab-cont tr > th:nth-child(9)').hide();

      $('#toInviteTab-cont tr > td:nth-child(1), #toInviteTab-cont tr > th:nth-child(1)').hide();
      $('#toInviteTab-cont tr > td:nth-child(5), #toInviteTab-cont tr > th:nth-child(5)').hide();
    if (insertIn == "#meetTab-cont") {
      $(insertIn).find("table").DataTable({
        "paging": false,
        "info": false,
        "responsive": true,
        "dom": '<"toolbar">frtip',
       
        // "columns":[
        //   { "data": 0 },
        //   { "data": 1 },
        //   { "data": 6 },
        //   { "data": 4 },
        //   { "data": 3 },
        //   { "data": 2 },
        //   { "data": 5 }
        // ]
        "columnDefs": [{
            "title": "creator",
            "targets": 0,
            "visible": true,
            "searchable": false
          },
          {
            "title": "meeting_id",
            "targets": 1,
            "visible": true,
            "searchable": false
          },
          {
            "title": "People",
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
            "title": "Date",
            "targets": 5,
            "visible": true,
            "searchable": true
          },
          {
            "title": "Time",
            "targets": 6,
            "visible": true,
            "searchable": true
          },
          {
            "title": "Role",
            "targets": 9,
            "visible": true,
            "searchable": true
          },
          {
            "title": "Option",
            "targets": 10,
            "visible": true,
            "searchable": true,
            "render": function(data, type, row) {
              if (row[9] == "C") {
                nd = '<button class="sho btn btn-outline-primary btn-sm" data-action="show" data-target="#meetModal" data-toggle="modal">Mostra</button><button class="mod btn btn-outline-primary btn-sm" data-action="update" data-target="#meetModal" data-toggle="modal">Modifica</button><button class="rem btn btn-danger btn-sm" data-action="remove" data-target="#meetModal" data-toggle="modal">Rimuovi</button><button class="mod btn btn-outline-primary btn-sm" data-action="" data-target="#inviteModal" data-toggle="modal">Invita</button>'
                return nd
              } else {
                nd = '<button class="sho btn btn-outline-primary btn-sm" data-action="show" data-target="#meetModal" data-toggle="modal">Mostra</button>'
                return nd
              }
            }
          }
        ]
      });
      $('#meetTab-cont tbody').on('click', 'tr', function() {
        datatab = $("#meetTab-cont").find("table").DataTable();
        if ($(this).hasClass('selected')) {
          // $(this).removeClass('selected');
        } else {
          datatab.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
          var data = datatab.row(this).data();
          //console.log(datatab)
          fetchPartTab({
            "meet_id": data[1]
          });
          fetchMeetRevTab({
            "meet_id": data[1]
          });
        }
      });
    } else if ((insertIn == "#invitedTab-cont")) {
      $(insertIn).find("table").DataTable({
        "paging": false,
        "info": false,
        "responsive": true,
        // "columns":[
        //   { "data": 0 },
        //   { "data": 1 },
        //   { "data": 6 },
        //   { "data": 4 },
        //   { "data": 3 },
        //   { "data": 2 },
        //   { "data": 5 }
        // ]
        "columnDefs": [{
          "title": "Name",
          "targets": 0,
        }, {
          "title": "Surname",
          "targets": 1,
        }, {
          "title": "Reply",
          "targets": 2,
          "render": function(data, type, row) {
            if (data == 0) {
              return "Pending"
            } else if (data == 1) {
              return "Joined"
            } else if (data == 2) {
              return "Refused"
            }
          }
        }, ]
      });
    } else if ((insertIn == "#toInviteTab-cont")){
      $(insertIn).find("table").DataTable({
      "paging": false,
      "info": false,
      "responsive": true
      
      });
      $("#toInviteTab-cont").find("[type='checkbox']").attr('name', "user_id[]");
    }
    $("table").css("width", "100%");
  }

  function createCell(cell, insertIn) {
    if (insertIn == "#toInviteTab-cont") {
      //Checkbox Button
      var row = $(cell).closest("tr"); // Find the row
      var cells = row.find("td");
      rad = $('<input type="checkbox"></input>').val(cells[0].innerText);
      $(cell).append(rad);
    } else {
    //Show Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Mostra"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "sho btn btn-outline-primary btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "show");
    div.setAttribute('data-target', "#meetModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    //Modify Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Modifica"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "mod btn btn-outline-primary btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "update");
    div.setAttribute('data-target', "#meetModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    //Remove Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Rimuovi"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "rem btn btn-danger btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "remove");
    div.setAttribute('data-target', "#meetModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    //Invite Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Invita"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "mod btn btn-outline-primary btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "update");
    div.setAttribute('data-target', "#meetModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    //Disdici Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Disdici"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "mod btn btn-outline-primary btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "update");
    div.setAttribute('data-target', "#meetModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    }
  }

  function createCardCoverflow(json, insertIn) {
    par = $(insertIn).parent();
    $(insertIn).remove();
    par.append('<div id="coverflow"><ul></ul></div>');
    $.each(data, function(i, item) {
      //console.log($(insertIn))
      txt = '<div data-card_id="' + item.card_id + '" class="card border-primary mb-3" style="max-width: 22rem;">  <div class="card-header">' + item.title + ' ' + item.name + ' ' + item.surname + '</div>  <div class="card-body text-primary"> <div class="row"><div class=" "><img class="image--cover medcard" src="'+item.photo+'" alt="" title="user-profile"> </div>  <div class="col">  <h5 class="card-title">'+item.work+ ' at '+item.company+'</h5>    <p class="card-text">'+item.edu+' at '+item.edu_place+'</p></div>   </div>  </div></div>'
      $(insertIn).find("ul").append("<li>" + txt + "</li>")
    })
    $(insertIn).find("ul").append("<li id='add'></li>");
    flip = $("#coverflow").flipster({
      onItemSwitch: fetchPartRevInfo,
      buttons: true
    });
    $("#add").remove();
    flip.flipster('jump', 0);
    flip.flipster('index');
  }

  function createMeetRev(json, insertIn) {
    $(insertIn).find(".autoupdate :input").off("change",autoUpdate);

    $(insertIn).find("textarea").val(json[0].note);
    $('#usefull').barrating('destroy');
    $('#importance').barrating('destroy');
    $('#usefull').val("");
    $('#importance').val("");
    $('#usefull').barrating({
      theme: 'fontawesome-stars-o',
      initialRating: json[0].usefull
    });
    $('#importance').barrating({
      theme: 'fontawesome-stars-o',
      initialRating: json[0].importance
    });
    $('#usefull').barrating('set', json[0].usefull);
    $('#importance').barrating('set', json[0].importance);

    $(insertIn).find(".autoupdate :input").on("change",autoUpdate);
  }

  function createPartRev(json, insertIn) {
    $(insertIn).find(".autoupdate :input").off("change",autoUpdate);

    $(insertIn).find('#card_id').val(json[0].card_id);
    $(insertIn).find('#name').html(json[0].name);
    $(insertIn).find('#surname').html(json[0].surname);
    if (json.length == 0) {
      //No entry yet
      $(insertIn).find('select').barrating('destroy');
      $(insertIn).find('select').val("");
      $(insertIn).find('select').barrating({
        theme: 'fontawesome-stars-o'
      });
      $(insertIn).find('textarea').val("");
      $(insertIn).attr("class", "autoinsert");
    } else {
      $('#professionality').barrating('destroy');
      $('#aviability').barrating('destroy');
      $('#impression').barrating('destroy');
      $('#professionality').val("");
      $('#aviability').val("");
      $('#impression').val("");
      $('#professionality').barrating({
        theme: 'fontawesome-stars-o',
        initialRating: json[0].professionality
      });
      $('#aviability').barrating({
        theme: 'fontawesome-stars-o',
        initialRating: json[0].aviability
      });
      $('#impression').barrating({
        theme: 'fontawesome-stars-o',
        initialRating: json[0].impression
      });
      $(insertIn).find("textarea").val(json[0].note);
      $('#professionality').barrating('set', json[0].professionality);
      $('#aviability').barrating('set', json[0].aviability);
      $('#impression').barrating('set', json[0].impression);
    }

    $(insertIn).find(".autoupdate :input").on("change",autoUpdate);
  }

  function sendPost(b, loc, addData = null, callback = null, closeModal = true) {
    sdata = $(b).serializeArray();
    console.log(sdata)
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
            if (closeModal) {
              $(b).closest(".modal").modal('toggle');
            }
          }
          return false;
        }
      }
    });
    return false;
  }
 
  function autoUpdate(e) {
    par = $(e.target).closest("form.autoupdate").parent();
    id = par.attr('id');
    console.log(id)
    if (id == "meetRev-cont") {
      datatab = $("#meetTab-cont").find("table").DataTable();
      var data = datatab.row('.selected').data();
      console.log(data);
      sendPost($(this).closest("form"), "meet-info-update-rev.php", [{
        name: "meet_id",
        value: data[1]
      }])
    } else if (id == "partRev-cont") {
      datatab = $("#meetTab-cont").find("table").DataTable();
      var data = datatab.row('.selected').data();
      console.log(data[1]);
      sendPost($(this).closest("form"), "wallet-info-update.php", [{
        name: "meet_id",
        value: data[1]
      }])
    }
  }

  //Dynamic Modal
  $('#meetModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    up = button.data('action');
    var modal = $(this);
    // Data from the row
    var row = button.closest("tr");
    var cells = row.find("td");
    $("input[name='meet_id']").remove();
    modal.find('form').append('<input style="display:none" type="number" name="meet_id" required>');
    $("input[name='meet_id']").val(cells[1].innerText);
    $("input[name='title']").val(cells[4].innerText);
    $("input[name='date']").val(cells[5].innerText);
    $("input[name='time']").val(cells[6].innerText);
    $("input[name='place']").val(cells[3].innerText);
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers.push(new google.maps.Marker({
      map: map,
      draggable: false,
      //   icon: icon,
      title: "Meeting",
      position: {
        lat: parseInt(cells[7].innerText),
        lng: parseInt(cells[8].innerText)
      }
    }));
    map.setCenter({
      lat: parseInt(cells[7].innerText),
      lng: parseInt(cells[8].innerText)
    })
    datatab = $("#meetTab-cont").find("table").DataTable();
    var data = datatab.row(".selected").data();
    console.log(datatab)
    fetchInvitedTab({
      "meet_id": data[1]
    });
    if (up == "update") {
      //Modal for Update
      modal.find('.modal-title').text('Update Education');
      modal.find('.btn-primary').text('Update');
      modal.find('.modal-footer').show();
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return updateMeeting(this)");
      //Fix for input
      modal.find('#invitedTab-cont').hide();
      modal.find(".modal-body").children().show()
      modal.find("input").removeClass("form-control-plaintext").addClass("form-control").attr("readonly", false)
    } else if (up == "remove") {
      //Modal for Remove
      modal.find('.modal-title').text('Remove this Meeting?');
      modal.find('.btn-primary').text('Remove');
      modal.find('.modal-footer').show();
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'meet-info-remove.php', null, fetchMeetTab)");
      //Fix Input Box
      modal.find(".modal-body").children().hide()
      modal.find("input").addClass("form-control-plaintext").removeClass("form-control").attr("readonly", true)
    } else if (up == "show") {
      //Modal for Show
      modal.find('.modal-title').text('Info Meeting');
      modal.find('.modal-footer').hide();
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "");
      //Fix Input Box
      modal.find('#invitedTab-cont').show();
      modal.find(".modal-body").children().show()
      modal.find("input").addClass("form-control-plaintext").removeClass("form-control").attr("readonly", true)
    }
  })
  $('#inviteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    up = button.data('action');
    var modal = $(this);
    // Data from the row
    var row = button.closest("tr");
    var cells = row.find("td");
    $("input[name='meet_id']").remove();
    modal.find('form').append('<input style="display:none" type="number" name="meet_id" required>');
    $("input[name='meet_id']").val(cells[1].innerText);

    fetchToInviteTab({
            "meet_id": cells[1].innerText
          });
  })
  var map
  var markers = [];

  function initAutocomplete() {
    map = new google.maps.Map(document.getElementById('mappe'), {
      center: {
        lat: 37.518932,
        lng: 15.083023
      },
      zoom: 13,
      mapTypeId: 'roadmap'
    });
    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input, {
      types: ['address']
    });
    console.log(searchBox)
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();
      if (places.length == 0) {
        return;
      }
      // Clear out the old markers.
      markers.forEach(function(marker) {
        marker.setMap(null);
      });
      markers = [];
      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }
        // var icon = {
        //   url: place.icon,
        //   size: new google.maps.Size(71, 71),
        //   origin: new google.maps.Point(0, 0),
        //   anchor: new google.maps.Point(17, 34),
        //   scaledSize: new google.maps.Size(25, 25)
        // };
        // Create a marker for each place.
        markers.push(new google.maps.Marker({
          map: map,
          draggable: true,
          //   icon: icon,
          title: "Meeting",
          position: place.geometry.location
        }));
        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
  }

  function updateMeeting(f) {
    var lat = markers[0].getPosition().lat();
    var lng = markers[0].getPosition().lng();
    sendPost(f, "meet-info-update.php", [{
      name: "lat",
      value: lat
    }, {
      name: "lng",
      value: lng
    }], fetchMeetTab);
    return false
  }

  </script>
  <!-- Google Maps-->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVtRz9Jsn_QTmIc8ml2I7bwU66a8mOxw&libraries=places&callback=initAutocomplete" async defer></script>
