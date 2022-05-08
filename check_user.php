<?php
include "db.php";
if (!isset($_GET["u"])) {
    die("Input Empty");
}

$user = $_GET["u"];
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$query= "SELECT * FROM mycard.account WHERE username = '$user' ";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

if($result->num_rows == 0){
    echo ('false');
} else {
    echo ('true');
}
?>