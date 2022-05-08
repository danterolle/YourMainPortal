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

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Create Meeting
        </div>
        <div class="card-body">

          <div id="wiz">
            <div id="page-1" class="page">
              <p class="display-4">Insert New Meeting Info</p>
              <form class="needs-validation">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input id="title" name="title" type="text" class="form-control " placeholder="Insert Meeting Title" required>
                  <div class="invalid-feedback">Required</div>
                </div>
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-6">
                      <label for="date">Date</label>
                      <input id="date" name="date" type="date" class=" form-control" required>
                      <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="col-md-6">
                      <label for="time">Time</label>
                      <input id="time" name="time" type="time" class="form-control" required>
                      <div class="invalid-feedback">Required</div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="pac-input">Location</label>
                  <input id="pac-input" name="place" type="text" class="controls form-control" placeholder="Address" required>
                </div>
                <div class="form-group">
                  <div id="mappe" style="height: 500px; width: 100%; border-radius: 20px;"></div>
                </div>
              </form>
              <div class="btn-group">
                <button class="btn" onclick="return nextPage(this)">choose your card</button>
              </div>
            </div>

            <div id="page-2" class="page">
              <div class="container-fluid">
              <form class="needs-validation">
                <div id="cardTab-cont" class="table-responsive"></div>
                <div class="invalid-feedback">Invalid</div>
              </form>
              </div>
              <div class="btn-group">
                <button class="btn" onclick="return prevPage(this)">Go back to meeting creation</button>
                <button class="btn" onclick="return nextPage(this)">invite to meeting </button>
              </div>
            </div>

            <div id="page-3" class="page">
              <form>
                <div id="userTab-cont"></div>
              </form>
              <button class="btn" onclick="return prevPage(this)">go back to Card select</button>
              <button class="btn" onclick="return sendWiz(this)">Finish</button>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- /#page-wrapper -->

  <script>
  $().ready(function() {
    $("#wiz").children().hide();
    $("#wiz").children().first().show();
  })

  function nextPage(c) {
    c = $(c)
    current = c.closest(".page");
    if (!valid(current)) return false;
    next = $(current).next().first();
    current.hide();
    next.show();
  }

  function prevPage(c) {
    c = $(c)
    current = c.closest(".page");
    prev = $(current).prev().first();
    current.hide();
    prev.show();
  }

  function valid(page) {
    pId = $(page).attr("id");
    console.log(pId)
    if (pId == "page-2") {
      console.log($(page).find(".selected"))
      if ($(page).find(".selected").length) {
        return true;
      }
      return false;
    } else {
      jform = $(page).find(".needs-validation");
      if (jform.length == 0) {
        return true;
      }
      form = jform.get(0);
      if (form.checkValidity() === true) {
        form.classList.add('was-validated');
        return true;
      } else {
        form.classList.add('was-validated');
        return false;
      }
    }
  }
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
        if (res.res == "false") {
          ret = false;
        } else if (res.res == "true") {
          data = res.data
          createCardCoverflow(data, "#cardTab-cont")
          ret = true;
        }
      }
    });
    return ret;
  }
  $.ready(fetchUserTab());

  function fetchUserTab() {
    ret = false;
    $.ajax({
      url: "user-info-fetch.php",
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
          CreateTableFromJSON(data, "#userTab-cont");
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
      createCell(table.rows[i].insertCell(table.rows[i].cells.length), "", 'col', insertIn);
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
      "responsive": true,
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

  function createCardCoverflow(json, insertIn, selectable=true) {
    par = $(insertIn).parent();
    $(insertIn).remove();
    par.append('<div id="' + insertIn.substring(1) + '"><ul></ul></div>');
    $.each(json, function(i, item) {
      console.log($(insertIn))
      txt = '<div data-card_id="' + item.card_id + '" class="card border-primary mb-3" style="max-width: 22rem;">  <div class="card-header">' + item.title + ' ' + item.name + ' ' + item.surname + '<div class="btn-group float-right"></div></div>  <div class="card-body text-primary"> <div class="row"><div class=" "><img class="image--cover medcard" src="' + item.photo + '" alt="" title="user-profile"> </div>  <div class="col">  <h5 class="card-title">' + item.work + ' at ' + item.company + '</h5>    <p class="card-text">' + item.edu + ' at ' + item.edu_place + '</p></div>   </div>  </div></div>'
      $(insertIn).find("ul").append("<li>" + txt + "</li>")
    })
    $(insertIn).find("ul").append("<li id='add'></li>");
    flip = $(insertIn).flipster({
      buttons: true
    });
    $("#add").remove();
    flip.flipster('jump', 0);
    flip.flipster('index');
    if (selectable) {
      //Needs mobile Fix! Problem with swipe!
      $(insertIn).find(".card").addClass("selectable");
      $(insertIn).find(".card").click(function() {
        if (!$(this).hasClass("selected") && $(this).closest(".flipster__item").hasClass("flipster__item--current")) {
          $(insertIn).find(".card.selected").removeClass("selected text-white bg-info").find(".card-body").removeClass("text-white").addClass("text-primary")
          $(this).addClass("selected text-white bg-info").find(".card-body").addClass("text-white").removeClass("text-primary")
        }
      });
    }
  }

  function sendWiz() {
    var lat = markers[0].getPosition().lat();
    var lng = markers[0].getPosition().lng();
    sendPost($("#page-1").find("form"), "meet-info-add.php", [{
      name: "lat",
      value: lat
    }, {
      name: "lng",
      value: lng
    }], saveId);
  }
  var meet_id;

  function sendPost(b, loc, addData = null, callback = null) {
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
          }
          return false;
        }
      }
    });
    return false;
  }

  function saveId(res) {
    meet_id = res.data.meet_id;
    console.log(meet_id);
    sendPost($("#page-2").find("form"), "part-info-add.php", [{
      name: "meet_id",
      value: meet_id
    },{
      name: "card_id",
      value: $("#page-2").find(".selected").data("card_id")
    }]);
    sendPost($("#page-3").find("form"), "invite-info-add.php", [{
      name: "meet_id",
      value: meet_id
    }], function(){
      
    });
    window.location.href = "meeting.php";
  }

  function check(value) {
    if (value === null) {
      setTimeout(check, 1000);
      return false;
    } else {
      return true;
    }
  }

  function test() {
    a = $("#page-1").find("form");
    sdata = $(a).serializeArray();
    addData = [{
      name: "ciao",
      value: "bello"
    }, {
      name: "cia",
      value: "bel"
    }]
    $.each(addData, function(i, d) {
      sdata.push(d);
    })
    console.log(sdata)
    sdata = $(a).serializeArray();
    addData = [{
      name: "ciao",
      value: "bello"
    }]
    $.each(addData, function(i, d) {
      sdata.push(d);
    })
    console.log(sdata)
  }
  var markers = [];

  function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('mappe'), {
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

  </script>

  <!-- Google Maps-->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVtRz9Jsn_QTmIc8ml2I7bwU66a8mOxw&libraries=places&callback=initAutocomplete" async defer></script>

</body>

</html>
