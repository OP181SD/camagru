<?php

namespace App\Models;

use PDO;
use App\Helpers\Database;

class Like
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function hasLiked(int $userId, int $publicationId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM likes WHERE user_id = :user_id AND publication_id = :publication_id"
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':publication_id' => $publicationId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function like(int $userId, int $publicationId): int
    {
        if ($this->hasLiked($userId, $publicationId)) {
            return $this->getLikesCount($publicationId);
        }

        $this->db->prepare(
            "INSERT INTO likes (user_id, publication_id) VALUES (:user_id, :publication_id)"
        )->execute([
            ':user_id' => $userId,
            ':publication_id' => $publicationId
        ]);
        $this->updateLikesCount($publicationId);

        return $this->getLikesCount($publicationId);
    }

    public function unlike(int $userId, int $publicationId): int
    {
        $this->db->prepare(
            "DELETE FROM likes WHERE user_id = :user_id AND publication_id = :publication_id"
        )->execute([
            ':user_id' => $userId,
            ':publication_id' => $publicationId
        ]);

        $this->updateLikesCount($publicationId);

        return $this->getLikesCount($publicationId);
    }

    public function getLikesCount(int $publicationId): int
    {
        $stmt = $this->db->prepare(
            "SELECT likes FROM publications WHERE id = :id"
        );
        $stmt->execute([':id' => $publicationId]);

        return (int) $stmt->fetchColumn();
    }
    private function updateLikesCount(int $publicationId): void
    {
        $this->db->prepare(
            "UPDATE publications 
             SET likes = (SELECT COUNT(*) FROM likes WHERE publication_id = :id)
             WHERE id = :id"
        )->execute([':id' => $publicationId]);
    }

    
}
