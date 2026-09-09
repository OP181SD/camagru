<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    private Notification $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new Notification();
    }
    public function updateUserCommentsNotification(int $userId, int $commentary): bool
    {
        return $this->notificationModel->updateNotifyComments($userId, $commentary);
    }
}
