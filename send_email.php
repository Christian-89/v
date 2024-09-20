<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\NutriFit\PHPMailer\src\Exception.php';
require 'C:\wamp64\www\NutriFit\PHPMailer\src\PHPMailer.php';
require 'C:\wamp64\www\NutriFit\PHPMailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El correo ingresado no es válido.";
        exit;
    }

    if (empty($name) || empty($email) || empty($message)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    // Instanciar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nutrifit722@gmail.com'; // Tu dirección de correo
        $mail->Password = 'nutrifit54#'; // Tu contraseña de Gmail o una contraseña de aplicaciones si tienes 2FA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;


        // Configuración del email
        $mail->setFrom($email, $name);
        $mail->addAddress('ramosvasedu@gmail.com'); // Correo de destino

        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje de contacto';
        $mail->Body = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";

        $mail->send();
        echo 'Correo enviado exitosamente.';
    } catch (Exception $e) {
        echo "Hubo un error al enviar el correo. Error: {$mail->ErrorInfo}";
    }
}
?>
