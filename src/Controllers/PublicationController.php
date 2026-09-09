<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\PublicationService;

class PublicationController
{
    private Application $response;
    private PublicationService $service;

    public function __construct()
    {
        $this->response = Application::getInstance();
        $this->service = new PublicationService();
    }

    public function createStickerPublication(): void
    {
        $data = $this->response->JsonToArray();

        $result = $this->service->createPublicationSticker($data);
        if ($result['status'] === 'success') {
            $this->response->jsonResponse($result, 200);
        } else {
            $this->response->jsonResponse($result, 400);
        }
    }

    public function createPicturePublication(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié.'
            ], 401);
            return;
        }

        $userId = (int)$_SESSION['user']['id'];


        $data = [
            'x' => $_POST['x'] ?? 0,
            'y' => $_POST['y'] ?? 0,
            'image' => $_FILES['image'] ?? null
        ];

        $result = $this->service->createPublicationPictures($userId, $data);

        if ($result['status'] === 'success') {
            $this->response->jsonResponse($result, 201);
        } else {
            $this->response->jsonResponse($result, 0);
        }
    }

    public function deletePublication(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié.'
            ], 401);
            return;
        }

        $userId = (int)$_SESSION['user']['id'];
        $data = $this->response->JsonToArray();

        $publicationId = isset($data['publication_id']) ? (int)$data['publication_id'] : 0;

        if ($publicationId <= 0) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'ID de publication invalide.'
            ], 400);
            return;
        }

        $result = $this->service->deletePublicationService($publicationId, $userId);

        $statusCode = $result['status'] === 'success' ? 200 : 400;
        $this->response->jsonResponse($result, $statusCode);
    }
}
