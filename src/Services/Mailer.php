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
        // 🔥 Ruta absoluta explícita (cambia si mueves el proyecto)
        $projectRoot = '/home/gemma/Escritorio/miweb-backend';

        // Verifica que exista
        if (!file_exists($projectRoot)) {
            throw new \RuntimeException("La carpeta del proyecto no existe: $projectRoot");
        }

        if (!file_exists("$projectRoot/.env")) {
            throw new \RuntimeException("No se encontró .env en: $projectRoot/.env");
        }

        // Carga el .env
        $dotenv = Dotenv::createImmutable($projectRoot);
        $dotenv->load();

        // Inicializa PHPMailer
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
            $this->mail->Body    = "
                <h3>Nuevo mensaje de contacto</h3>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Mensaje:</strong><br>$mensaje</p>
            ";

            $this->mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("Error al enviar correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}