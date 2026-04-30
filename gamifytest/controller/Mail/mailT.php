<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->SMTPDebug = 0;                      // Enable verbose debug output

    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'manejodedatos2020@gmail.com';                     // SMTP username
    $mail->Password   = '123456789admin.';                               // SMTP password
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('manejodedatos2020@gmail.com', 'ADMIPN');
    $mail->addAddress('yebtani1999@gmail.com');     // Add a recipient
    

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Un asunto de recuperacion de contraseña';
	$mail->Body    = 'Veamos que parte de esto es posible  <b>Su contraseña es: taltaltal123</b>';
    $mail->AltBody = 'Mensaje alternativo';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}



?>