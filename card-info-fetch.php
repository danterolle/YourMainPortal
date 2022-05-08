<?php

session_start();
if (!isset($_SESSION["user_id"])) {die("No Login! <a href='home.php'> Log here! </a>");}
include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

$query = "SELECT 
C.card_id, 
C.user_id, 
C.title, 
C.name ,
C.surname, 
C.email, 
C.phone, 
C.photo, 
C.note ,
C.education_experience_id, 
C.work_experience_id ,
E.title edu, 
E.place edu_place, 
W.role work,
Co.name company
FROM mycard.cards C 
join mycard.work_experience W on W.work_experience_id = C.work_experience_id  
join mycard.education_experience E on E.education_experience_id = C.education_experience_id
join mycard.company Co on Co.company_id = W.company_id
WHERE C.user_id = '$user_id' ";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

if($result){
    $arr=mysqli_fetch_all($result, MYSQLI_ASSOC);
    $ans=array("res"=>"true", "data"=>$arr);
    print_r(json_encode($ans));
}else{
    $ans=array("res"=>"false", "data"=>"null");
    print_r(json_encode($ans));
}
?>