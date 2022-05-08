<?php
session_start();
if (!isset($_POST["meet_id"])) die("Input Empty");

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Leggi dati
$title = mysqli_real_escape_string($conn, $_POST["title"]);
$place = mysqli_real_escape_string($conn, $_POST["place"]);
$date = mysqli_real_escape_string($conn, $_POST["date"]);
$time = mysqli_real_escape_string($conn, $_POST["time"]);
$lat = mysqli_real_escape_string($conn, $_POST["lat"]);
$lng = mysqli_real_escape_string($conn, $_POST["lng"]);
$meet_id = mysqli_real_escape_string($conn, $_POST["meet_id"]);
$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$query = "UPDATE `mycard`.`meeting` SET `title`='$title', `date`= '$date $time', `place`='$place', `lat`='$lat', `lng`='$lng' WHERE `meeting_id`='$meet_id';";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

if ($result) {
    $ans = array("res" => "true", "data" => null);
    print_r(json_encode($ans));
} else {
    $ans = array("res" => "false", "data" => null);
    print_r(json_encode($ans));
}
