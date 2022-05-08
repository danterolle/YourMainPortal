
<?php
if (!isset($_POST["username"])) {
    die("Input Empty");
}

include "db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Leggi dati
$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);
$name = mysqli_real_escape_string($conn, $_POST["name"]);
$surname = mysqli_real_escape_string($conn, $_POST["surname"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$date = mysqli_real_escape_string($conn, $_POST["date"]);

$password = md5($password);

mysqli_begin_transaction($conn);

$query = "INSERT INTO `mycard`.`account` (`username`, `activation_date`, `passw`) VALUES ('" . $username . "','" . date("Y-m-d") . "', '" . $password . "');";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

$last_id = mysqli_insert_id($conn);

$query = "INSERT INTO `mycard`.`users` (`user_id`,`name`, `surname`, `birth`, `email`) VALUES ('$last_id','$name', '$surname', '$date', '$email');";
$result2 = mysqli_query($conn, $query);
if (!$result2) {
    echo ('<div class="error">' . mysqli_error($conn) . '<div>');
}

if ($result and $result2) {
    mysqli_commit($conn);
    $ans = array("res" => true, "data" => null);
    print_r(json_encode($ans));
} else {
    mysqli_rollback($conn);
    $ans = array("res" => false, "data" => null);
    print_r(json_encode($ans));
}

?>
