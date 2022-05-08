<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
//if meet_id is set will return all the invite for that meeting, reutrn invite for that user in the other case
if (isset($_GET["meet_id"])) {
    $meet_id = mysqli_real_escape_string($conn, $_GET["meet_id"]);
    $query = "SELECT U.name, U.Surname, I.reply from users U join invite I on I.user_id=U.user_id
    where I.meeting_id='$meet_id' ORDER BY I.reply ASC";

}else{
    $query = "SELECT M.meeting_id meet_id, M.title, M.place, date(M.date) date, time(M.date) time, I.reply from meeting M join invite I on I.meeting_id=M.meeting_id
    where I.user_id='$user_id' ORDER BY I.reply ASC";
    
}

$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($result) {
    $res = array("res" => "true");
    $ans = array("res" => "true", "data" => $arr);
    print_r(json_encode($ans));
} else {
    $res = array("res" => "false");
    $ans = array("res" => "false", "data" => "null");
    print_r(json_encode($ans));
}

?>
