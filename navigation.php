<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
  <a class="navbar-brand" href="dashboard.php">The Homework</a>
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarResponsive">
    <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
        <a class="nav-link" href="dashboard.php">
          <i class="fa fa-fw fa-dashboard"></i>
          <span class="nav-link-text">Home</span>
        </a>
      </li>
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Your Profile">
        <a class="nav-link" href="your-profile.php">
          <i class="fa fa-fw fa-area-chart"></i>
          <span class="nav-link-text">Your Profile</span>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav sidenav-toggler">
      <li class="nav-item">
        <a class="nav-link text-center" id="sidenavToggler">
          <i class="fa fa-fw fa-angle-left"></i>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto ">

      <li class="nav-item dropdown ">
        <a style="padding:0px" class="nav-link dropdown-toggle mr-lg-2" id="profileDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span style="padding:0px 10px 0 0" class="align-middle nav-text success"><i class="fa fa-fw fa-user"></i>Ciao <?php echo $_SESSION["username"]?>  </span>
        <img class="image--cover small" src="<?php echo $_SESSION["photo"]?>" alt="" title="user-profile">
        </a>
        
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="user-info.php">
            <i class="fa fa-fw fa-gear"></i>
            <span class="nav-link-text">Profilo</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<div id="logoutModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Uscire?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="logout()" class="btn btn-primary">Fammi uscire asap</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin.js"></script>

<script>
function logout() {
  $.ajax({
    method: "GET",
    url: "logout.php",
    success: function() {
      window.location.href = "home.php";
    }
  });
}
$.ready(fetchInvite())
pending = 0

function fetchInvite() {
  $.ajax({
    method: "GET",
    url: "invite-info-fetch.php",
    dataType: "json",
    success: function(res) {
      console.log(res);
      if (res.res == "false") {
        return false;
      } else if (res.res == "true") {
        data = res.data;
        $.each(data, function checkInvite(i, item) {
            if (item.reply == 0) {
              pending = pending + 1;
            }
          }
        );

        if (pending != 0) {
          console.log($("[data-original-title*='Invites']"))
          $("[data-original-title*='Invites']").find("span").append('<span class="badge badge-pill badge-primary">'+pending+' Pending</span>');
        }
      }
      return false;
    }
  });
}

</script>
