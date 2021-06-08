<?php
    session_start();
//Librerias PHP-Mailer

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	require '/var/www/PHPMailer/src/Exception.php';
	require '/var/www/PHPMailer/src/PHPMailer.php';
	require '/var/www/PHPMailer/src/SMTP.php';

	$mail= $_SESSION["correo"];
	$username= $_SESSION["nombreCorreo"];

$email = new PHPMailer();
$email->isSMTP();

//Enable SMTP debugging
//SMTP::DEBUG_OFF = off (for production use)
//SMTP::DEBUG_CLIENT = client messages
//SMTP::DEBUG_SERVER = client and server messages
$email->SMTPDebug = SMTP::DEBUG_SERVER;

$email->Host = 'smtp.gmail.com';
$email->Port = 587;
$email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$email->SMTPAuth = true;

//Usuario
$email->Username = 'autowordpressphp@gmail.com';
$email->Password = 'autowordpressphp1-';

//Remitente
$email->setFrom('autowordpressphp@gmail.com', 'Auto Wordpress');
$email->addReplyTo('m.rodrigueztorrado@gmail.com', 'Manuel Rodriguez'); //Alt remitente

//Set who the message is to be sent to
$email->addAddress($mail, $username);

//Set the subject line
$email->Subject = 'Entrega de credenciales Wordpress';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$email->msgHTML(file_get_contents('/var/www/ficheros/contents.html'), __DIR__);

//Replace the plain text body with one created manually
$email->AltBody = 'Prueba de texto';

if (!$email->send()) {
	echo 'Mailer Error: ' . $email->ErrorInfo;
} else {
	echo 'Message sent!';
}

unset($_SESSION["correo"]);	
unset($_SESSION["nombreCorreo"]);


?>