<?php
        session_start();
        if (!isset($_POST["w_id"])) die("Input Empty");
        if (!isset($_SESSION["user_id"])) die("No Login! <a href='home.php'> Log here! </a>");

        include("db.php");

        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }    
        
        $company = mysqli_real_escape_string($conn, $_POST["company"]);
        $year = mysqli_real_escape_string($conn, $_POST["year"]);
        $place = mysqli_real_escape_string($conn, $_POST["place"]);
        $role = mysqli_real_escape_string($conn, $_POST["role"]);
        $w_id = mysqli_real_escape_string($conn, $_POST["w_id"]);
        $user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

        $query= "UPDATE `mycard`.`work_experience` SET `company_id`='$company', `year`='$year', `place`='$place', `user_id`='$user_id', `role`='$role' WHERE `work_experience_id`='$w_id';";
        $result = mysqli_query($conn, $query);
        if(!$result) echo('<div class="error">'. mysqli_error($conn) .'<div>');
        
        if($result){
            $ans= array("res"=>"true", "data"=>NULL);
            print_r(json_encode($ans));
        }else{
            $ans= array("res"=>"false", "data"=>NULL);
            print_r(json_encode($ans));
        }
        
?>