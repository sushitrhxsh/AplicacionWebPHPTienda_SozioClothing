<?php 
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

class Mailer {
    function enviarEmail($email, $asunto, $cuerpo) {
        require_once './config/conexion.php';
        require './includes/phpmailer/src/PHPMailer.php';
        require './includes/phpmailer/src/SMTP.php';
        require './includes/phpmailer/src/Exception.php';

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; //'smtp.office365.com'; // host de envio gmail o outlook
            $mail->SMTPAuth   = true;
            $mail->Username   = ''; // Tu correo personal ya sea gmail o outlook
            $mail->Password   = ''; // Contrasenia de gmail (debe estar ya configurado para enviar por correos desde gmail) si es de outlook solo ponle la contrasenia que tienes en ese correo y ya
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //ENCRYPTION_STARTTLS; //seguridad de envio de correos
            $mail->Port       = 465; //587;

            // Recipients
            $mail->setFrom('', 'Tienda sozioclothing'); // poner el correo principal de donde se vaa enviar
            $mail->addAddress($email, 'Joe User');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = utf8_decode($cuerpo);
            $mail->setLanguage('es', '/tienda_ropa/phpmailer/lenguaje/phpmailer.lang-es.php');

            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
            return false;
        }
    }
}

?>