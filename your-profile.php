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
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col md-6">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-graduation-cap"></i> Education Experience
              <button style="float:right;" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#eduModal">
                <i class="fa fa-plus"></i> Aggiungi</button>
            </div>
            <div class="card-body">
              <div id="edu-tab" class="table-responsive"></div>
            </div>
          </div>
        </div>

        <div class="col md-6">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-briefcase"></i> Work Experience
              <button style="float:right;" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#workModal">
                <i class="fa fa-plus"></i> Aggiungi</button>
            </div>
            <div class="card-body">
              <div id="work-tab" class="table-responsive"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="eduModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Aggiungi Education Experience</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="edu-info-add.php">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input class="form-control" type="text" name="title" placeholder="Titolo" required>
                  <label for="year">Year</label>
                  <input class="form-control" type="number" name="year" placeholder="Year" required>
                  <label for="place">Place</label>
                  <input class="form-control" type="text" name="place" placeholder="Place" required>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Salva</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="work-info-add.php">
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-4">
                      <button type="button" class="btn" data-toggle="modal" data-action="insert" data-target="#compModal">Add Company</button>
                    </div>
                    <div class="col-md-8">
                      <label for="company">Company</label>
                      <select id="company" class="form-control" type="select" name="company" placeholder="Company" required></select>
                    </div>
                  </div>
                  <label for="year">Year</label>
                  <input class="form-control" type="number" name="year" placeholder="Year" required>
                  <label for="place">Place</label>
                  <input class="form-control" type="text" name="place" placeholder="Place" required>
                  <label for="role">Role</label>
                  <input class="form-control" type="text" name="role" placeholder="Role" required>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="compModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Aggiungi Compagnia</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="comp-info-add.php">
                <input type="text" name="name" placeholder="Nome Compagnia" required>
                <input type="text" name="placeC" placeholder="Place" required>
                <input type="text" name="web" placeholder="Website" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Aggiungi</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- /#page-wrapper -->

</body>

</html>

<script>
  function fetchEduTab() {
    $.ajax({
      url: "edu-info-fetch.php",
      type: 'GET',
      async: true,
      cache: false,
      timeout: 30000,
      dataType: "json",
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
          CreateTableFromJSON(data, "#edu-tab");
        }
      }
    });
    // return res;
  }

  function fetchWorkTab() {
    ret = false;
    $.ajax({
      url: "work-info-fetch.php",
      type: 'GET',
      async: true,
      cache: false,
      dataType: "json",
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
          CreateTableFromJSON(data, "#work-tab");
          ret = true;
        }
      }
    });
    return ret;
  }

  function refreshCompSel() {
    ret = false;
    $.ajax({
      url: "comp-info-fetch.php",
      type: 'GET',
      async: true,
      cache: false,
      timeout: 30000,
      dataType: "json",
      error: function () {
        return false;
      },
      success: function (res) {
        console.log(res);
        //res = JSON.parse(res);
        if (res.res == "false") {
          ret = false;
        } else if (res.res == "true") {
          data = res.data
          addToOption(data, $("select[name='company']"));
          ret = true;
        }
      }
    });
    return ret;
  }
  $(document).ready(fetchEduTab);
  $(document).ready(fetchWorkTab);
  $(document).ready(refreshCompSel);

  function sendPost(b, loc, callback) {
    $.ajax({
      url: loc,
      type: 'POST',
      data: $(b).serialize(),
      async: true,
      cache: false,
      timeout: 30000,
      dataType: "json",
      error: function () {
        return false;
      },
      success: function (res) {
        console.log(res);
        //res = JSON.parse(resp);
        if (res.res == "false") {
          return false;
        } else if (res.res == "true") {
          callback();
          $(b).closest(".modal").modal('toggle');
          return false;
        }
      }
    });
    return false;
  }

  function addToOption(json, select) {
    select.find("option").remove();
    $.each(json, function (index, json) {
      select.append($("<option></option>").attr("value", json.company_id).text(json.name));
    });
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
      createCell(table.rows[i].insertCell(table.rows[i].cells.length), "", 'col');
    }
    $(table).prepend(header);
    //table created
    divContainer.append(table);
    //Add Bootstrap
    $(insertIn).find("table").addClass("table table-striped table-bordered");
    //DataTable
    switch (insertIn) {
      case '#edu-tab':
        //Hide column
        $('#edu-tab tr > td:nth-child(5), #edu-tab tr > th:nth-child(5)').hide();
        $('#edu-tab tr > td:nth-child(1), #edu-tab tr > th:nth-child(1)').hide();
        //Set right modal for button
        $("#edu-tab").find(".mod").attr('data-target', "#eduModal");
        $("#edu-tab").find(".rem").attr('data-target', "#eduModal");
        $(insertIn).find("table").DataTable({
          "paging": false,
          "info": false,
          "columnDefs": [{
              "title": "ex_id",
              "targets": 0,
              "visible": true,
              "searchable": false
            },
            {
              "title": "Titolo di Studio",
              "targets": 1,
              "visible": true,
              "searchable": true
            },
            {
              "title": "Anno",
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
              "title": "user_id",
              "targets": 4,
              "visible": true,
              "searchable": false
            },
            {
              "title": "Modifica",
              "targets": 5,
              "visible": true,
              "searchable": false
            }
          ]
        });
        break;
      case '#work-tab':
        $('#work-tab tr > td:nth-child(2), #work-tab tr > th:nth-child(2)').hide();
        $('#work-tab tr > td:nth-child(1), #work-tab tr > th:nth-child(1)').hide();
        $("#work-tab").find(".mod").attr('data-target', "#workModal");
        $("#work-tab").find(".rem").attr('data-target', "#workModal");
        $(insertIn).find("table").DataTable({
          "paging": false,
          "info": false,
          "columnDefs": [{
              "title": "c_id",
              "targets": 0,
              "visible": true,
              "searchable": false
            },
            {
              "title": "w_id",
              "targets": 1,
              "visible": true,
              "searchable": false
            },
            {
              "title": "Compagnia",
              "targets": 2,
              "visible": true,
              "searchable": true
            },
            {
              "title": "Ruolo",
              "targets": 3,
              "visible": true,
              "searchable": true
            },
            {
              "title": "Anno",
              "targets": 4,
              "visible": true,
              "searchable": true
            },
            {
              "title": "Luogo",
              "targets": 5,
              "visible": true,
              "searchable": true
            },
            {
              "title": "Modifica",
              "targets": 6,
              "visible": true,
              "searchable": false
            }
          ]
        });
        break;
    }
    $("table").css("width", "100%");
  }

  function createCell(cell, text, style) {
    //Modify Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Modifica"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "mod btn btn-outline-primary btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "update");
    div.setAttribute('data-target', "#eduModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
    //Remove Button
    var div = document.createElement('button'); // create DIV element
    txt = document.createTextNode("Rimuovi"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "rem btn btn-danger btn-sm"); // set DIV class attribute
    div.setAttribute('data-action', "remove");
    div.setAttribute('data-target', "#eduModal");
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell
  }
  //Dynamic Modal
  $('#eduModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    up = button.data('action');
    if (up == "update") {
      //Modal for Update
      var modal = $(this);
      modal.find('.modal-body').show();
      modal.find('.modal-title').text('Update Education');
      modal.find('.btn-primary').text('Update');
      //console.log(modal.find('form'));
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'edu-info-update.php', fetchEduTab)");
      var row = button.closest("tr"); // Find the row
      var cells = row.find("td");
      $("input[name='title']").val(cells[1].innerText);
      $("input[name='year']").val(cells[2].innerText);
      $("input[name='place']").val(cells[3].innerText);
      $("input[name='ex_id']").remove();
      modal.find('form').append('<input style="display:none" type="number" name="ex_id" required>');
      $("input[name='ex_id']").val(cells[0].innerText);
    } else if (up == "remove") {
      var row = button.closest("tr"); // Find the row
      var cells = row.find("td");
      checkEduDep({
            "edu_id": cells[0].innerText
          });
      var modal = $(this);
      modal.find('.modal-body').hide();
      modal.find('.modal-title').text('Remove Education');
      modal.find('.btn-primary').text('Remove');
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'edu-info-remove.php', fetchEduTab)");
      //Fix Input Box
      var row = button.closest("tr"); // Find the row
      var cells = row.find("td");
      $("input[name='title']").val(cells[1].innerText);
      $("input[name='year']").val(cells[2].innerText);
      $("input[name='place']").val(cells[3].innerText);
      $("input[name='ex_id']").remove();
      modal.find('form').append('<input style="display:none" type="number" name="ex_id" required>');
      $("input[name='ex_id']").val(cells[0].innerText);
    } else {
      //Modal for Insert
      var modal = $(this);
      modal.find('.modal-body').show();
      modal.find('.modal-title').text('Insert Education');
      modal.find('.btn-primary').text('Send');
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'edu-info-add.php', fetchEduTab)");
      $("#eduModal").find("input").val("");
      $("input[name='ex_id']").remove();
    }
  })
  $('#workModal').on('show.bs.modal', function (event) {
    //up="false";
    var button = $(event.relatedTarget) // Button that triggered the modal
    up = button.data('action');
    if (up == "update") {
      //Update Modal
      var modal = $(this);
      modal.find('.modal-body').show();
      modal.find('.modal-title').text('Update Work');
      modal.find('.btn-primary').text('Update');
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'work-info-update.php', fetchWorkTab)");
      //Fix input box for update
      var row = button.closest("tr"); // Find the row
      var cells = row.find("td");
      $("select[name='company']").val(cells[1].innerText);
      $("input[name='year']").val(cells[4].innerText);
      $("input[name='place']").val(cells[5].innerText);
      $("input[name='role']").val(cells[3].innerText);
      $("input[name='w_id']").remove();
      modal.find('form').append('<input style="display:none;" type="number" name="w_id" required>');
      $("input[name='w_id']").val(cells[0].innerText);
    } else if (up == "remove") {
      var row = button.closest("tr"); // Find the row
      var cells = row.find("td");
      checkWorkDep({
            "work_id": cells[0].innerText
          });
      var modal = $(this);
      modal.find('.modal-body').hide();
      modal.find('.modal-title').text('Remove Education');
      modal.find('.btn-primary').text('Remove');
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'work-info-remove.php', fetchWorkTab)");
      //Fix input box
      $("select[name='company']").val(cells[1].innerText);
      $("input[name='year']").val(cells[4].innerText);
      $("input[name='place']").val(cells[5].innerText);
      $("input[name='role']").val(cells[3].innerText);
      $("input[name='w_id']").remove();
      modal.find('form').append('<input style="display:none;" type="number" name="w_id" required>');
      $("input[name='w_id']").val(cells[0].innerText);
    } else {
      var modal = $(this);
      modal.find('.modal-body').show();
      modal.find('.modal-title').text('Insert Work');
      modal.find('.btn-primary').text('Send');
      //modal.find('form').attr("action", "work-info-add.php");
      modal.find('form').attr("action", "");
      modal.find('form').attr("onsubmit", "return sendPost(this,'work-info-add.php', fetchWorkTab)");
      //Clear input box
      $("#workModal").find("input").val("");
      $("#workModal").find("select").val("");
      $("input[name='w_id']").remove();
    }
  })
  $('#compModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    up = button.data('action');
    switch (up) {
      case "insert":
        var modal = $(this);
        modal.find('form').attr("onsubmit", "return sendPost(this,'comp-info-add.php', refreshCompSel)");
        $("#compModal").find("input").val("");
        break;
    }
  })
  // $(document).ready( function () {
  //     $('#edu-tab > table').DataTable();
  // } );

    function checkWorkDep(dataIn) {
    $.ajax({
      url: "work-info-check-dep.php",
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
        } else if (res.res == "true") {
          data = res.data
          if(data.length !== 0){
            alert("If you delete this entry, the cards containing it will also be deleted")
            return false
          }
          return true;
        }
      }
    });}


  function checkEduDep(dataIn) {
    $.ajax({
      url: "edu-info-check-dep.php",
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
        } else if (res.res == "true") {
          data = res.data
          if(data.length !== 0){
            alert("If you delete this entry, the cards containing it will also be deleted")
            return false
          }
          return true;
        }
      }
    });

  }
</script>