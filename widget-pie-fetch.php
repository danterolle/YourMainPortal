<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$query = 
"SELECT title, reply, count(*) count from
(SELECT title, meeting_id from meeting M where user_id=$user_id order by date desc limit 1 ) MId 
    join invite I on MId.meeting_id=I.meeting_id
group by reply";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

// ADDED ROLE
$arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
$label=[];
$d=[];
$da=[];
$data=[];
foreach ($arr as &$val){
array_push($da, $val["count"]);
}
$label=["Pending","Joined","Refused"];
$d["data"]=$da;
$c=["#007BFF", "#28A745", "#DC3545"];
$d["backgroundColor"]=$c;
array_push($data,$d);
if($result){
    $ans=array("label"=>$label, "data"=>$data);
    print_r(json_encode($ans));
}else{
    $res= array("res"=>"false");
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}
?>
