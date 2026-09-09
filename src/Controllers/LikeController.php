<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\LikeService;

class LikeController
{
    private Application $response;
    private LikeService $service;

    public function __construct()
    {
        $this->response = Application::getInstance();
        $this->service = new LikeService();
    }

    public function likesCount(): void
    {
        $data = $this->response->JsonToArray();
        $publicationId = $data['publication_id'] ?? null;
        $userId = $_SESSION['user']['id'];
        $liked = $data['liked'] ?? null;

        $result = $this->service->toggleLike((int)$userId, (int)$publicationId, (bool)$liked);
        $statusCode = $result['status'] === 'success' ? 200 : 500;
        $this->response->jsonResponse($result, $statusCode);
    }

    public function likesStatus(): void
    {
        $publicationId = $_GET['publication_id'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$publicationId || !$userId) {
            $this->response->jsonResponse(['status' => 'error', 'message' => 'Paramètres manquants'], 400);
            return;
        }

        $result = $this->service->getLikeStatus((int)$userId, (int)$publicationId);
        $statusCode = $result['status'] === 'success' ? 200 : 500;
        $this->response->jsonResponse($result, $statusCode);
    }
}
