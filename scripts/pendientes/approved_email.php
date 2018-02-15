<?php

//require_once('phpmailer/class.phpmailer.php');

include('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');


$para = 'leva2020@gmail.com';
$asunto = 'prueba de envio';
$mensaje = 'mensaje de prueba';

$mail = new PHPMailer();

/*$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;*/

$mail->IsSMTP();
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 465;
$mail->SMTPAuth = true;

//$mail->SMTPDebug = 2;
$mail->Username = "quot.developer@gmail.com";
$mail->Password = "developers";

$mail->From = 'quot.developer@gmail.com';
//$mail->AddAddress('leva2020@gmail.com', 'El Destinatario');
$mail->Subject = 'Esto es un correo de prueba';
$mail->AltBody = "Este es un mensaje de prueba.";

$mail->AddAddress("leva2020@gmail.com");                 // Correo destino
$mail->IsHTML(TRUE);

$mail->Body = "Mensaje de prueba";

//Enviamos el correo
if (!$mail->Send()) {
    echo "Error: " . $mail->ErrorInfo;
} else {
    echo "Enviado!";
}