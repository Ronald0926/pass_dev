<!DOCTYPE html>
  <html>
    <head>
      <title>Mi quinta página con php</title>
    </head>
    <body>
<?php

require ("Mail.php");
echo("<p> jaja!</p>");
$from = "<team@nationalhealthp.com>";
$to = "Fredy Mendoza <fredy.mendoza@tecnolet.com>";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";
$host = "smtp.ip-zone.com";
$username = "nationalhealthp";
$password = "7f3ba211";
$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));
$mail = $smtp->send($to, $headers, $body);

if (isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
 } else {
  echo("<p>Message successfully sent!</p>");
 }
?>
  </body>
  </html>
