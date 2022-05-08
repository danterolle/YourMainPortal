<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
$work_id = mysqli_real_escape_string($conn, $_GET["work_id"]);

$query = "SELECT * FROM mycard.cards WHERE work_experience_id = '$work_id' ";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

if($result){
    $res= array("res"=>"true");
    $arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
    $ans=array("res"=>"true", "data"=>$arr);
    print_r(json_encode($ans));
}else{
    $res= array("res"=>"false");
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}

?>