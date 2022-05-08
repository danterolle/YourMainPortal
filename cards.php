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

<div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove this Card?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" >
                      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   
    <div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-id-card"></i> Your Cards
              <a style="float:right;" href="card-new.php?u=false"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Aggiungi Carta</button></a>
        </div>
        <div class="card-body">
        <div id="cardTab-cont" class="table-responsive"></div>
        </div>
      </div>
        <!-- /#page-wrapper -->

</body>

<script>
$.ready(fetchCardTab());

function fetchCardTab() {
    ret=false;
    $.ajax({
        url: "card-info-fetch.php",
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
                //CreateTableFromJSON(data, "#cardTab-cont");
                createCardCoverflow(data, "#cardTab-cont")
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
        createCell(table.rows[i].insertCell(table.rows[i].cells.length), "", 'col');
    }

    $(table).prepend(header);
    //table created
    divContainer.append(table);
    //Add Bootstrap
    $(insertIn).find("table").addClass("table table-striped table-bordered");
    //DataTable
    // $('#cardTab-cont tr > td:nth-child(1), #cardTab-cont tr > th:nth-child(1)').hide();
    // $('#cardTab-cont tr > td:nth-child(2), #cardTab-cont tr > th:nth-child(2)').hide();

            $(insertIn).find("table").DataTable({
                "paging": false,
                "info": false,
                "responsive":true,
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



    //Hide column



    //Set right modal for button
    $("#cardTab-cont").find(".rem").attr('data-target', "#cardModal");

}

function createCell(cell, text, style) {
     var row = $(cell).closest("tr"); // Find the row
    var cells = row.find("td");
    
    //Modify Button
    link= document.createElement('a');
    link.setAttribute('href', "./card-new.php?u=true&id="+ cells[0].innerText);

    var div = document.createElement('button'); // create DIV element
    var txt = document.createTextNode("Modifica"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "mod btn btn-outline-primary btn-sm"); // set DIV class attribute
    link.appendChild(div);
    cell.appendChild(link); // append DIV to the table cell
   
    //Remove Button
    var div = document.createElement('button'); // create DIV element
        txt = document.createTextNode("Rimuovi"); // create text node
    div.appendChild(txt); // append text node to the DIV
    div.setAttribute('class', "rem btn btn-danger btn-sm"); // set DIV class attribute
    div.setAttribute('data-toggle', "modal");
    cell.appendChild(div); // append DIV to the table cell

}

function createCardCoverflow(json, insertIn) {
    par = $(insertIn).parent();
    $(insertIn).remove();
    par.append('<div id="'+insertIn.substring(1)+'"><ul></ul></div>');
    $.each(json, function(i, item) {
      console.log($(insertIn))
      txt = '<div data-card_id="' + item.card_id + '" class="card border-primary mb-3" style="max-width: 22rem;">  <div class="card-header">' + item.title + ' ' + item.name + ' ' + item.surname + '<div class="btn-group float-right"><button class="mod btn btn-outline-primary btn-sm " onclick=goToUpdate()><i class="fa fa-pencil"></i></button><button class="rem btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#cardModal"><i class="fa fa-times"></i> </button></div></div>  <div class="card-body text-primary"> <div class="row"><div class=" "><img class="image--cover medcard" src="'+item.photo+'" alt="" title="user-profile"> </div>  <div class="col">  <h5 class="card-title">'+item.work+ ' at '+item.company+'</h5>    <p class="card-text">'+item.edu+' at '+item.edu_place+'</p></div>   </div>  </div></div>'
      $(insertIn).find("ul").append("<li>" + txt + "</li>")
    })
    $(insertIn).find("ul").append("<li id='add'></li>");
    flip = $(insertIn).flipster({
      buttons: true
    });
    $("#add").remove();
    flip.flipster('jump', 0);
    flip.flipster('index');
  }

function sendPost(b, loc, callback) {
    $.ajax({
        url: loc,
        type: 'POST',
        data: $(b).serialize(),
        async: true,
        cache: false,
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
                callback();
                $(b).closest(".modal").modal('toggle');
                return false;
            }
        }
    });
return false;
}

function goToUpdate(){
   card_id=$('.flipster__item--current').find(".card").data("card_id");
window.location.href = 'card-new.php?u=true&id='+card_id+'';
}

$('#cardModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    card=$(button).closest(".card")
        var modal = $(this);
        modal.find('.modal-title').text('Remove Card');
        modal.find('.btn-primary').text('Remove');
        modal.find('form').attr("action", "");
        modal.find('form').attr("onsubmit", "return sendPost(this,'card-info-remove.php', fetchCardTab)");

        //Fix Input Box

        $("input[name='card_id']").remove();
        modal.find('form').append('<input style="display:none" type="number" name="card_id" required>');
        $("input[name='card_id']").val(card.data("card_id"));
})

</script>
