<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'nombre_usuario';
$password = 'tu_contraseña';
$dbName = 'nombre_base_de_datos';

// Nombre del archivo de copia de seguridad
$backupFile = 'copia_de_seguridad_' . date('Y-m-d_H-i-s') . '.sql';
$backupZipFile = $backupFile . '.gz';

// Crear la copia de seguridad y comprimirla
exec("mysqldump -h $host -u $user -p$password $dbName | gzip > $backupZipFile");

// Verificar si la copia de seguridad fue creada exitosamente
if (!file_exists($backupZipFile)) {
    error_log("Error: No se pudo crear la copia de seguridad.");
    exit(1);
}

// Configuración del correo
$to = 'destinatario@correo.com';
$subject = 'Copia de Seguridad de la Base de Datos';
$body = 'Adjunto encontrarás la copia de seguridad de la base de datos.';
$from = 'remitente@tu-dominio.com';

// Cabeceras del correo
$headers = "From: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

// Cuerpo del mensaje
$boundary = "boundary";
$message = "--$boundary\r\n";
$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$message .= "$body\r\n\r\n";
$message .= "--$boundary\r\n";
$message .= "Content-Type: application/octet-stream; name=\"$backupZipFile\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n";
$message .= "Content-Disposition: attachment; filename=\"$backupZipFile\"\r\n\r\n";
$message .= chunk_split(base64_encode(file_get_contents($backupZipFile))) . "\r\n";
$message .= "--$boundary--";

// Enviar el correo con el archivo adjunto
if (mail($to, $subject, $message, $headers)) {
    echo "Copia de seguridad enviada exitosamente a $to.";
} else {
    error_log("Error: No se pudo enviar la copia de seguridad por correo.");
    exit(1);
}

// Limpieza del archivo de copia de seguridad
unlink($backupZipFile);
?>
