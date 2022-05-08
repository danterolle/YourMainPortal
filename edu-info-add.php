<?php
        session_start();
        if (!isset($_POST["title"])) die("Input Empty");
        if (!isset($_SESSION["user_id"])) die("No Login! <a href='home.php'> Log here! </a>");

        include("db.php");

        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }    
    
	// Leggi dati
        $title = mysqli_real_escape_string($conn, $_POST["title"]);
        $year = mysqli_real_escape_string($conn, $_POST["year"]);
        $place = mysqli_real_escape_string($conn, $_POST["place"]);
        $user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

        $query = "INSERT INTO `mycard`.`education_experience` (`title`, `year`, `place`, `user_id`) VALUES ('" . $title . "','" . $year . "', '" . $place . "', '" . $user_id . "');";
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