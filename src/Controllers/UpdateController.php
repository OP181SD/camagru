<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\UpdateService;

class UpdateController
{
    private UpdateService $updateService;
    private Application $response;

    public function __construct()
    {
        $this->updateService = new UpdateService();
        $this->response =  Application::getInstance();
    }

    public function settingsUpdate(): void
    {
        $data = $this->response->JsonToArray();

        if (!isset($_SESSION['user']['id'])) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Utilisateur non connecté'
            ], 401);
            return;
        }

        $userId = $_SESSION['user']['id'];

        try {
            $result = $this->updateService->updateSettings($userId, $data);

            $this->response->jsonResponse($result);
        } catch (\Exception $e) {
            error_log('[APP ERROR] settingsUpdate: ' . $e->getMessage());
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur serveur'
            ], 500);
        }
    }
}
