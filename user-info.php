<!Doctype html>
<html>
<?php
session_start();
require "head.html";

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

include "db.php";
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$result = mysqli_query($conn, "SELECT * FROM mycard.users WHERE user_id = '$user_id' ");
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

$result1 = mysqli_query($conn, "SELECT * FROM mycard.account WHERE user_id = '$user_id' ");
if (!$result1) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}
?>

  <body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <?php require 'navigation.php';?>

    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> Profile info
              </div>
              <div class="card-body">
                <?php
							$row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
							if ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								echo ('
									<form id="info-form" method="post" onsubmit="return sendPic(this,\'user-update-info.php\')" >
									<table>
										<tr>
											<th>
												Username
											</th>
											<td>
												<input disabled readonly class=".plain-text" value="' . $row1["username"] . '" type="text" name="username" placeholder="Username" required>
											</td>
										</tr>
										<tr>
											<th>
												Email
											</th>
											<td>
												<input value="' . $row["email"] . '" type="email" name="email" placeholder="Email" required>
											</td>
										</tr>
										<tr>
											<th>
												Nome
											</th>
											<td>
												<input value="' . $row["name"] . '" type="text" name="name" placeholder="Nome" required>
											</td>
										</tr>
										<tr>
											<th>
												Cognome
											</th>
											<td>
												<input value="' . $row["surname"] . '" type="text" name="surname" placeholder="Cognome" required>
											</td>
										</tr>
										<tr>
											<th>
												Data
											</th>
											<td>
												<input value="' . $row["birth"] . '" type="date" name="date" placeholder="Data" required>
											</td>
										</tr>
									</table>
									<button> Update </button>
								</form>');
							}
?>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fa fa-table"></i> Profile Image
              </div>
              <div class="card-body">
                <img class="image--cover" src="<?php echo $row["photo"]?>" alt="Profile picture" title="wide image">
                <form id="pic-form" onsubmit="return sendPic(this,'profile-info-image-upload.php')" enctype="multipart/form-data">
                  Select image to upload:
                  <input type="file" name="fileToUpload" id="fileToUpload">
                  <button type="submit" name="submit"> Upload Image</button>
                </form>
              </div>
            </div>
          </div>
        </div>
  </body>

</html>

<script>
function sendPic(b, loc, addData = null, callback = null, closeModal = true) {
  var form = $(b);
  var formdata = false;
  if (window.FormData) {
    formdata = new FormData(form[0]);
  }
  var formAction = form.attr('');
  $.ajax({
    url: loc,
    data: formdata ? formdata : form.serialize(),
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
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

</script>
