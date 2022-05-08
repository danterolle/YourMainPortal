<?php
        session_start();
        if (!isset($_POST["title"])) die("Input Empty");
        if (!isset($_SESSION["user_id"])) die("No Login! <a href='home.php'> Log here! </a>");

        include("db.php");
        error_reporting(E_ALL ^ E_NOTICE);  
        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
	       // Leggi dati
        $title = mysqli_real_escape_string($conn, $_POST["title"]);
        $place = mysqli_real_escape_string($conn, $_POST["place"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $time = mysqli_real_escape_string($conn, $_POST["time"]);
        $lat = mysqli_real_escape_string($conn, $_POST["lat"]);
        $lng = mysqli_real_escape_string($conn, $_POST["lng"]);
        $user_id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

        $query = "INSERT INTO `mycard`.`meeting` (`user_id`, `title`, `place`, `date`, `lat`, `lng`) VALUES ('" . $user_id . "','" . $title. "', '" . $place . "', '" . $date . " " .$time ."','" . $lat. "','" . $lng. "');";
        $result = mysqli_query($conn, $query);
        if(!$result) echo('<div class="error">'. mysqli_error($conn) .'<div>');
        $last_id = mysqli_insert_id($conn);
        $arr["meet_id"]=$last_id;
        if($result){
            $ans= array("res"=>"true", "data"=>$arr);
            print_r(json_encode($ans));
        }else{
            $ans= array("res"=>"false", "data"=>NULL);
            print_r(json_encode($ans));
        }

?>
