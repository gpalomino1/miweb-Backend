<?php

namespace App\Application\Actions\Contacto;

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

    public function __invoke(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();

        $nombre = $data['nombre'] ?? '';
        $email = $data['email'] ?? '';
        $mensaje = $data['mensaje'] ?? '';

        if (empty($nombre) || empty($email) || empty($mensaje)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Todos los campos son obligatorios'
            ]));
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        // Guardar en la base de datos
        $sql = "INSERT INTO mensajes (nombre, email, mensaje) VALUES (?, ?, ?)";
        // Enviar correo electrónico
        if ($this->mailer->sendContactEmail($nombre, $email, $mensaje)) {
            $response->getBody()->write(json_encode([
                'status' => 'ok',
                'message' => 'Hemos recibido tu mensaje. Nos pondremos en contacto pronto.'
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Hubo un error al procesar tu solicitud. Por favor, inténtalo más tarde.'
            ]));
            return $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
        }
        }
    }
