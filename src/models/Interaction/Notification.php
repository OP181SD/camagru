<?php

namespace App\Models;

use PDO;
use App\Helpers\Database;


class Notification
{
    private PDO $request;

    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }

    public function updateNotifyComments(int $userId, int $commentary): bool
    {
        try {
            $sql = "UPDATE users SET notify_comments = :notify_comments, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->request->prepare($sql);

            $stmt->bindValue(':notify_comments', $commentary, PDO::PARAM_BOOL);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {

            return false;
        }
    }
}
