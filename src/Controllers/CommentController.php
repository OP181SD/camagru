<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\CommentService;

class CommentController
{
    private CommentService $commentService;
    private Application $response;

    public function __construct()
    {
        $this->commentService = new CommentService();
        $this->response = Application::getInstance();
    }

    public function insertCommentary(): void
    {
        $data = $this->response->JsonToArray();
        $publicationId = $data['publication_id'] ?? null;
        $content = trim($data['content'] ?? '');

        if (!$publicationId || empty($content) || !isset($_SESSION['user']['id'])) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Données invalides ou utilisateur non connecté'
            ], 400);
            return;
        }

        $userId = $_SESSION['user']['id'];

        try {
            $commentsData = $this->commentService->addComment($publicationId, $userId, $content);

            $this->response->jsonResponse([
                'status' => 'success',
                'comments_count' => $commentsData['comments_count'],
                'created_at' => $commentsData['created_at']          
            ], 200);
        } catch (\Exception $e) {
            error_log('[APP ERROR] insertCommentary: ' . $e->getMessage());
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur serveur'
            ], 500);
        }
    }
}
