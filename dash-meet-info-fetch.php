<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$query = "SELECT A.meeting_id, creator, partecipanti, place, title, rating from
(SELECT M.user_id creator, M.meeting_id, count(*) as partecipanti, M.place, M.title from meeting M join partecipate P on M.meeting_id=P.meeting_id
where M.meeting_id in (Select meeting_id from partecipate where user_id='$user_id')
group by M.meeting_id) A left join 

(select meeting_id, avg((professionality+impression+aviability)/3) as rating from wallet W join  cards C on C.card_id=W.card_id
where c.user_id='$user_id'
group by meeting_id) B on A.meeting_id = B.meeting_id;";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

//Aggiunta ruolo
$arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($arr as &$val){
  if($val["creator"]==$user_id){
    $val["role"]="C";
  }else{
    $val["role"]="P";
  }
}

if($result){
    $res= array("res"=>"true");
    $ans=array("res"=>"true", "data"=>$arr);
    print_r(json_encode($ans));
}else{
    $res= array("res"=>"false");
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}
?>
