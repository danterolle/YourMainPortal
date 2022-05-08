<?php
// Swift Mailer Library
require_once '..\vendor\autoload.php';

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername ('d3f4lt_930@gmail.com')
  ->setPassword ('lowqualitytest');

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Invite'))
   ->setFrom (array('d3f4lt_930@gmail.com' => 'My Card'))
   ->setTo (array('test@mydomain.com' => 'User'))
   ->setSubject ('New Invite')
   ->setBody ('Test Message', 'text/html');

$result = $mailer->send($message);

?>