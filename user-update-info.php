<?php
ob_start();
session_start();
if (!isset($_POST["email"])) {
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
$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

//$username = mysqli_real_escape_string($conn, $_POST["username"]);
//$password = mysqli_real_escape_string($conn, $_POST["password"]);
$name = mysqli_real_escape_string($conn, $_POST["name"]);
$surname = mysqli_real_escape_string($conn, $_POST["surname"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$date = mysqli_real_escape_string($conn, $_POST["date"]);

// $query= "UPDATE `mycard`.`account` SET `username`='$username', `password`='$password' WHERE `user_id`='$user_id';";
// $result = mysqli_query($conn, $query);
// if(!$result) echo('<div class="error">'. mysqli_error($conn) .'<div>');

$query = "UPDATE `mycard`.`users` SET `name`='$name', `surname`='$surname', `birth`='$date', `email`='$email' WHERE `user_id`='$user_id';";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

//controllo
$result = mysqli_query($conn, "SELECT * FROM mycard.users WHERE user_id = '$user_id' ");
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

$err = ob_get_contents();
ob_end_clean();
if ($result) {
    $ans = array("res" => "true", "data" => null, "err" => $err);
    print_r(json_encode($ans));
} else {
    $ans = array("res" => "false", "data" => null, "err" => $err);
    print_r(json_encode($ans));
}

?>