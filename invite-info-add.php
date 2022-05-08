<?php
session_start();
if (!isset($_POST["meet_id"])) {
    die("Input Empty");
}

if (!isset($_SESSION["user_id"])) {
    die("No Login! <a href='home.php'> Log here! </a>");
}

include "db.php";
// Swift Mailer Library
require_once '..\vendor\autoload.php';
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('d3f4lt_930@gmail.com')
    ->setPassword('lowqualitytest');
$mailer = new Swift_Mailer($transport);

print_r($_POST["user_id"]);
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Leggi dati
$meeting_id = mysqli_real_escape_string($conn, $_POST["meet_id"]);

foreach ($_POST['user_id'] as $user) {
    //invite
    $query = "INSERT INTO `mycard`.`invite` (`user_id`, `meeting_id`) VALUES ('" . $user . "','" . $meeting_id . "');";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo ('<div class="error">' . mysqli_error($conn) . '<div>');
    }

    //mail
    $query = "SELECT *from `mycard`.`users` U join meeting where U.user_id=$user and meeting_id=$meeting_id ";
    $result1 = mysqli_query($conn, $query);
    if (!$result1) {
        echo ('<div class="error">' . mysqli_error($conn) . '<div>');
    }

    $arr = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $message = (new Swift_Message('Invite'))
        ->setFrom(array('d3f4lt_930@gmail.com' => 'My Card'))
        ->setTo(array($arr['email'] => $arr['name']))
        ->setSubject('New Invite')
        ->setBody('<div> You received a new invite for '.$arr['title'].' meeting.</div> <a href="http://localhost/yourmainportal/invites.php">Check out your invite!</a>', 'text/html');

    $result = $mailer->send($message);

}

if ($result) {
    $ans = array("res" => "true", "data" => null);
    print_r(json_encode($ans));
} else {
    $ans = array("res" => "false", "data" => null);
    print_r(json_encode($ans));
}

?>