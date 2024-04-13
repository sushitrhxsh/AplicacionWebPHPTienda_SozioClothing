<?php 
use PHPMailer\PHPMailer\{PHPMailer,SMTP,Exception};


require '/tienda_ropa/includes/phpmailer/src/PHPMailer.php';
require '/tienda_ropa/includes/phpmailer/src/SMTP.php';
require '/tienda_ropa/includes/phpmailer/src/Exception.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //SMTP::DEBUG_SERVER;          
    $mail->isSMTP();                                            
    $mail->Host       = MAIL_HOST;                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = MAIL_USER;                     
    $mail->Password   = MAIL_PASS;                              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = MAIL_PORT;                   //if u use 587  `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(MAIL_USER, 'Tienda sozioclothing');
    $mail->addAddress('pruebacorreo12@yopmail.com', 'Joe User');   
    //$mail->addReplyTo('info@example.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalle de su compra';

    $cuerpo = "<h4>Gracias por su compra</h4>";
    $cuerpo.= "<p>El ID de su compra es <b>".$id_transaccion."</b></p>";

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra';
    $mail->setLanguage('es','/tienda_ropa/phpmailer/lenguaje/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico no de la compra: {$mail->ErrorInfo}";
   //exit;
}

?>