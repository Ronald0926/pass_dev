<!DOCTYPE html>
  <html>
    <head>
      <title>Mi quinta página con php</title>
    </head>
    <body>
    
 <?php
 error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");
include('Mail.php');

$recipients = 'manuel.fernandoanzola@gmail.com.com';

$headers['From']    = 'manuel.anzola@tecnolet.com';
$headers['To']      = 'manuel.fernandoanzola@gmail.com';
$headers['Subject'] = 'Test message';

$body = 'Test message';

$params['sendmail_path'] = '../Mail/sendmail';

// Create the mail object using the Mail::factory method
$mail_object =& Mail::factory('sendmail', $params);

$mail_object->send($recipients, $headers, $body);
?>
  </body>
  </html>
