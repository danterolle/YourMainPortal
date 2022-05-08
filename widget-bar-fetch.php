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
"SELECT M.user_id c, title label, avg(usefull) u, avg(importance) i from meeting M 
join partecipate P on M.meeting_id=P.meeting_id
where M.user_id = $user_id
group by M.meeting_id";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

//ADDED ROLE
$arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
$label=[];
$ud=[];
$id=[];
$u=[];
$i=[];
$data=[];
foreach ($arr as &$val){
array_push($label, $val["label"]);
array_push($ud, $val["u"]);
array_push($id, $val["i"]);
}
$u["label"]="Usefull";
$u["backgroundColor"]="#50E350";
$u["data"]=$ud;
$i["label"]="Importance";
$i["backgroundColor"]="#50E3C2";
$i["data"]=$id;
array_push($data,$i,$u);
if($result){
    $ans=array("label"=>$label, "data"=>$data);
    print_r(json_encode($ans));
}else{
    $res= array("res"=>"false");
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}

?>
