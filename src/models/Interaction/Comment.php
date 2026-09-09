<?php

namespace App\Models;

use PDO;
use App\Helpers\Database;

class Comment
{
    private PDO $request;

    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }

    public function commentaryInsert(int $publicationId, int $userId, string $content): array
    {
        try {
            $createdAt = date('Y-m-d H:i:s');


            $stmt = $this->request->prepare("
                INSERT INTO comments (user_id, publication_id, content, created_at)
                VALUES (:user_id, :publication_id, :content, :created_at)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':publication_id' => $publicationId,
                ':content' => $content,
                ':created_at' => $createdAt
            ]);

            $stmtUpdate = $this->request->prepare("
                UPDATE publications
                SET comments_count = comments_count + 1
                WHERE id = :publication_id
            ");
            $stmtUpdate->execute([':publication_id' => $publicationId]);

            $stmtCount = $this->request->prepare("
                SELECT comments_count FROM publications WHERE id = :publication_id
            ");
            $stmtCount->execute([':publication_id' => $publicationId]);
            $commentsCount = (int)$stmtCount->fetchColumn();

            return [
                'comments_count' => $commentsCount,
                'created_at' => $createdAt
            ];
        } catch (\Exception $e) {
            error_log('[APP ERROR] Comment::addComment : ' . $e->getMessage());
            return [
                'comments_count' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
    }

    public function getPublicationOwner(int $publicationId): ?array
    {
        try {
            $sql = "
                SELECT u.id, u.username, u.email, u.notify_comments
                FROM users u
                INNER JOIN publications p ON p.user_id = u.id
                WHERE p.id = :publication_id
                LIMIT 1
            ";
            $stmt = $this->request->prepare($sql);
            $stmt->execute([':publication_id' => $publicationId]);

            $owner = $stmt->fetch(PDO::FETCH_ASSOC);
            return $owner ?: null;
        } catch (\PDOException $e) {
            error_log('[APP ERROR] Comment::getPublicationOwner : ' . $e->getMessage());
            return null;
        }
    }

    public function getUserById(int $userId): ?array
    {
        try {
            $sql = "SELECT id, username FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->request->prepare($sql);
            $stmt->execute([':id' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }
}
