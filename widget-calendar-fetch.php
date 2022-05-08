<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$query = "SELECT M.user_id creator, M.meeting_id, count(*) as partecipanti, M.place, M.title title, DATE_FORMAT(M.date, '%Y-%m-%d') date , DATE_FORMAT(M.date, '%h:%i') time, lat, lng from meeting M join partecipate P on M.meeting_id=P.meeting_id
where M.meeting_id in (Select meeting_id from partecipate where user_id='$user_id')
group by M.meeting_id";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

// ADDED ROLE
$arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($arr as &$val){
  if($val["creator"]==$user_id){
    $val["role"]="C";
  }else{
    $val["role"]="P";
  }
}

if($result){
    print_r(json_encode($arr));
}else{
    $res= array("res"=>"false");
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}

?>
