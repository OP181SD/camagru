<?php


namespace App\Core;

use App\Exceptions\JsonException;

class Application
{
    private static ?self $instance = null;
    private ?Router $router = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }


    public function JsonToArray(): array
    {
        $Rawstring = file_get_contents('php://input');
        $data = json_decode($Rawstring, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(400, [
                'status' => 'errors',
                'message' => ['Erreur : le corps JSON est invalide.']
            ]);
        }
        return $data;
    }

    public function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function run(): void
    {
        if ($this->router === null) {
            throw new \RuntimeException("Le router doit être assigné avant d'exécuter run().");
        }
        try {
            $this->router->run();
        } catch (JsonException $e) {
            $this->jsonResponse($e->getData(), $e->getStatus());
        } catch (\Throwable $e) {
            $this->jsonResponse([
                'status'  => 'errors',
                'message' => [$e->getMessage()],
            ], 500);
        }
    }
}