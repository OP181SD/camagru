<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\NotificationService;

class NotificationController
{
    private NotificationService $notificationService;
    private Application $response;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
        $this->response = Application::getInstance();
    }

    public function updateCommentNotification(): void
    {
        $data = $this->response->JsonToArray();
        $userId = $data['user_id'] ?? null;
        $commentary = $data['notify_comments'] ?? null;

        if (!$userId || !isset($commentary)) {
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Données invalides'
            ], 400);
            return;
        }

        try {
            $updated = $this->notificationService->updateUserCommentsNotification($userId, (int)$commentary);

            if ($updated) {
                $_SESSION['notify_comments'] = (int)$commentary;

                $this->response->jsonResponse([
                    'status' => 'success',
                    'message' => 'Notification mise à jour'
                ], 200);
            } else {
                $this->response->jsonResponse([
                    'status' => 'error',
                    'message' => 'Impossible de mettre à jour la notification'
                ], 500);
            }
        } catch (\Exception $e) {
            error_log('[APP ERROR] updateCommentNotification: ' . $e->getMessage());
            $this->response->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur serveur'
            ], 500);
        }
    }
}
