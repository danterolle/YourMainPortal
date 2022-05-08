<?php
session_start();
ob_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include("db.php");
$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
$target_dir = "uploads/pr_$user_id";
$target_file = $target_dir ."_". basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 20000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "$target_dir.$imageFileType")) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        $query = "UPDATE `mycard`.`users` SET `photo`='$target_dir.$imageFileType' where user_id='$user_id' ;";
        $result = mysqli_query($conn, $query);
        $_SESSION["photo"] = "$target_dir.$imageFileType";
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
$err=ob_get_contents();
ob_end_clean();
if ($uploadOk) {
    $ans = array("res" => "true", "data" => null, "err"=>$err);
    print_r(json_encode($ans));
} else {
    $ans = array("res" => "false", "data" => null, "err"=>$err);
    print_r(json_encode($ans));
}
?>