<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->SMTPDebug = 2;                                  
    $mail->isSMTP();                                       
    $mail->Host = 'smtp.tu-servidor.com';  
    $mail->SMTPAuth = true;                                
    $mail->Username = 'tu_correo@tu-dominio.com';          
    $mail->Password = 'tu_contraseña';                     
    $mail->SMTPSecure = 'tls';                             
    $mail->Port = 587;                                     

    // Destinatarios
    $mail->setFrom('tu_correo@tu-dominio.com', 'Tu Nombre');
    $mail->addAddress('destinatario@correo.com');           

    // Adjuntar archivo
    $mail->addAttachment($backupZipFile);         

    // Contenido del correo
    $mail->isHTML(true);                                  
    $mail->Subject = 'Copia de Seguridad de la Base de Datos';
    $mail->Body    = 'Adjunto encontrarás la copia de seguridad de la base de datos.';
    
    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
}
?>
