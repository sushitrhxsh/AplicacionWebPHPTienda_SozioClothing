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
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'l19260901@matamoros.tecnm.mx'; //mis credenciales
            $mail->Password   = 'xoq14770';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('l19260901@matamoros.tecnm.mx', 'Tienda sozioclothing');
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