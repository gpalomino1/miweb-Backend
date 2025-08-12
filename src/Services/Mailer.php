<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

class Mailer
{
    private $mail;

    public function __construct()
    {
        // Carga las variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../..'); // va a la raíz del proyecto
        $dotenv->load();

        $this->mail = new PHPMailer(true);
    }

    public function sendContactEmail($nombre, $email, $mensaje): bool
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['MAILER_USERNAME'];
            $this->mail->Password   = $_ENV['MAILER_PASSWORD'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;

            $this->mail->setFrom($email, $nombre);
            $this->mail->addAddress('infocodewm@gmail.com', 'Recepción de Mensajes');
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Nuevo mensaje desde el sitio web';
            $this->mail->Body    = "Nombre: $nombre<br>Email: $email<br>Mensaje: $mensaje";

            $this->mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("Error al enviar correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}