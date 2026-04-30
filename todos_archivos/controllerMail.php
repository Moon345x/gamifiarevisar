<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Mail/Exception.php';
require 'Mail/PHPMailer.php';
require 'Mail/SMTP.php';

class MailerW
{
	public function sendRecoveryEmail($correo,$username,$passwrd)
	{
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
			$mail->setFrom('manejodedatos2020@gmail.com', 'ADMIN DATAS');
			$mail->addAddress($correo);     // Add a recipient
			

			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Recuperar la clave de ingreso';
			$mail->Body    = 'Su peticion para recuperar sus datos ha sido atendida: <br> Su usuario es: <b>'.$username.'</b> <br>Su contraseña es: <b>'.$passwrd.'</b>';
			// $mail->AltBody = 'Mensaje alternativo';
			$mail->send();

			// echo 'Message has been sent';
			return array( "result" => "SUCCESS", "message"=>"Un correo con sus datos ha sido enviado.");
		} catch (Exception $e) {
			return array( "result" => "ERROR", "message"=>"ha habido un problema con el envio de su información, por favor intente más tarde");
			
			// echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
}

?>