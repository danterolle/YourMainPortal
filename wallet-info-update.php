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
$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
$meet_id = mysqli_real_escape_string($conn, $_POST["meet_id"]);
$card_id = mysqli_real_escape_string($conn, $_POST["card_id"]);

$note = mysqli_real_escape_string($conn, $_POST["note"]);
$professionality = mysqli_real_escape_string($conn, $_POST["professionality"]);
$aviability = mysqli_real_escape_string($conn, $_POST["aviability"]);
$impression = mysqli_real_escape_string($conn, $_POST["impression"]);

$query = "SELECT W.note, professionality, impression, aviability from wallet W
        where W.card_id='$card_id' and W.meeting_id='$meet_id' and W.user_id='$user_id';";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}


if (mysqli_num_rows($result) == 0) {
    //Caso Add
    $query = "INSERT INTO `mycard`.`wallet` (`meeting_id`, `user_id`, `card_id`) VALUES ('" . $meet_id. "','" . $user_id . "', '" . $card_id . "');";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo ('<div class="error">' . mysqli_error($conn) . '<div>');
    }
}

$query = "UPDATE `mycard`.`wallet` SET `note`='$note', `professionality`='$professionality', `aviability`='$aviability',`impression`='$impression'
                where card_id = '$card_id' and meeting_id='$meet_id' and user_id='$user_id' ;";
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