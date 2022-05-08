<?php
        session_start();
        if (!isset($_POST["ex_id"])) die("Input Empty");
        if (!isset($_SESSION["user_id"])) die("No Login! <a href='home.php'> Log here! </a>");

        include("db.php");

        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }    
    
	// Leggi dati
        $ex_id = mysqli_real_escape_string($conn, $_POST["ex_id"]);

        $query= "DELETE FROM `mycard`.`education_experience` WHERE `education_experience_id`='$ex_id';";
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