<?php
session_start();
include "db.php";

if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    die("Input Empty");
}

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Leggi dati
$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);

// Cripta password
$password = md5($password);

// Cerca la coppia (username, password) nel database
$query= "SELECT * FROM mycard.account WHERE username = ? AND passw = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        echo ('<div class="error">' . mysqli_error($conn) . '<div>');
    }
}

//If exist entry set photo
if ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    // Salva login in sessione
    $_SESSION["username"] = $row["username"];
    $_SESSION["user_id"] = $row["user_id"];

    if ($stmt = mysqli_prepare($conn, "SELECT * FROM mycard.users WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION["user_id"]);
        $stmt->execute();
        $result1 = $stmt->get_result();
        if (!$result1) {
            echo ('<div class="error">' . mysqli_error($conn) . '<div>');
        }
    }    

    if ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
        // Salva login in sessione
        $_SESSION["photo"] = $row["photo"];
    } else {
        $_SESSION["photo"] = "uploads/def.png";
    }

}

if (mysqli_num_rows($result) == 0) {
    $not_empty = false;
} else {
    $not_empty = true;
}

if ($result) {
    $ans = array("res" => true, "not_empty" => $not_empty, "data" => null);
    print_r(json_encode($ans));
} else {
    $ans = array("res" => false, "not_empty" => $not_empty, "data" => null);
    print_r(json_encode($ans));
}

?>
