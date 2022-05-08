<?php
session_start();
if (!isset($_POST["meet_id"])) {
    die("Input Empty");
}

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Leggi dati
$meet_id = mysqli_real_escape_string($conn, $_POST["meet_id"]);
$reply = mysqli_real_escape_string($conn, $_POST["reply"]);
$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);


$query = "UPDATE `mycard`.`invite` SET `reply`='$reply'
                where meeting_id = '$meet_id' and user_id='$user_id' ;";
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

?>