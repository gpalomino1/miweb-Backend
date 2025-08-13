<?php

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;
use App\Services\Mailer;

class ContactoAction
{
    private $pdo;
    private $mailer;

    public function __construct(PDO $pdo, Mailer $mailer)
    {
        $this->pdo = $pdo;
        $this->mailer = $mailer;
    }
//---------------------------------------------------------------------------------------//
public function __invoke(Request $request, Response $response): Response
{
    $data = (array)$request->getParsedBody();

    $nombre = $data['nombre'] ?? '';
    $email = $data['email'] ?? '';
    $mensaje = $data['mensaje'] ?? '';

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $payload = json_encode([
            'status' => 'error',
            'message' => 'Todos los campos son obligatorios'
        ]);
        $response->getBody()->write($payload);
        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }

    try {
        // 1. Guardar en la base de datos
        $sql = "INSERT INTO mensajes (nombre, email, mensaje) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $mensaje]);

        // 2. Intentar enviar correo (opcional, no crítico)
        try {
            $this->mailer->sendContactEmail($nombre, $email, $mensaje);
            // Si falla, no importa, el mensaje ya se guardó
        } catch (\Exception $e) {
            // Solo registra el error, no lo muestra al usuario
            error_log("Correo no enviado: " . $e->getMessage());
        }

        // 3. Responder al frontend
        $payload = json_encode([
            'status' => 'ok',
            'message' => 'Hemos recibido tu mensaje. Nos pondremos en contacto pronto.'
        ]);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');

    } catch (\Exception $e) {
        // Este catch es SOLO para errores de base de datos
        error_log("Error al guardar en BD: " . $e->getMessage());
        $payload = json_encode([
            'status' => 'error',
            'message' => 'Error al guardar el mensaje'
        ]);
        $response->getBody()->write($payload);
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json');
    }
}
    //---------------------------------------------------------------------------------------//
}