<?php

namespace App\Models;

use App\Helpers\Database;
use PDO;
use PDOException;
use Exception;

class Publication
{
    private PDO $request;
    private static ?self $instance = null;

    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function customDirectory(): string
    {
        $uploadDir = __DIR__ . '/../public/assets/uploads/publications/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        return $uploadDir;
    }

    public function generateNameFile(array $file): string
    {
        $fileName = $file['name'] ?? 'photo.png';
        $ext = pathinfo($fileName, PATHINFO_EXTENSION) ?: 'png';
        return uniqid('pub_', true) . '.' . $ext;
    }

    public function createPublication(int $userId, string $photo, string $sticker = '', int $x = 0, int $y = 0, array $file = [])
    {
        try {

            $uploadDir = $this->customDirectory();
            $fileName = $this->generateNameFile($file);
            $outputPath = $uploadDir . '/' . $fileName;

            $photoType = mime_content_type($photo);
            if (!$photoType || !in_array($photoType, ['image/png', 'image/jpeg', 'image/jpg',])) {
                return null;
            }

            $photoImg = ($photoType === 'image/png')
                ? imagecreatefrompng($photo)
                : imagecreatefromjpeg($photo);


            $stickerPath = !empty($sticker) ? __DIR__ . '/../public' . $sticker : '';
            if (!empty($stickerPath) && file_exists($stickerPath)) {
                $stickerImg = imagecreatefrompng($stickerPath);
                imagecopy($photoImg, $stickerImg, $x, $y, 0, 0, imagesx($stickerImg), imagesy($stickerImg));
             
            }

            ($photoType === 'image/png')
                ? imagepng($photoImg, $outputPath)
                : imagejpeg($photoImg, $outputPath);


  

            $createdAt = date('Y-m-d H:i:s');

            $publicPath = '/assets/uploads/publications/' . $fileName;

            $stmt = $this->request->prepare(" INSERT INTO publications (user_id, image_path, sticker, x, y, created_at) VALUES (:user_id, :image_path, :sticker, :x, :y, :created_at)");
            $stmt->execute([
                ':user_id' => $userId,
                ':image_path' => $publicPath,
                ':sticker' => $sticker,
                ':x' => $x,
                ':y' => $y,
                ':created_at' => $createdAt
            ]);

            return (int) $this->request->lastInsertId();
        } catch (\Exception $e) {
            error_log('[APP ERROR] createPublication : ' . $e->getMessage());
            return null;
        }
    }

    public function deletePublication(int $publicationId, int $userId): bool
    {
        try {
            $stmt = $this->request->prepare("SELECT image_path FROM publications WHERE id = :id AND user_id = :user_id");
            $stmt->execute([
                ':id' => $publicationId,
                ':user_id' => $userId
            ]);
            $publication = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$publication) {
                return false;
            }

            $imageFullPath = __DIR__ . '/../public' . $publication['image_path'];
            if (file_exists($imageFullPath)) {
                unlink($imageFullPath);
            }

            $stmtDelete = $this->request->prepare("DELETE FROM publications WHERE id = :id AND user_id = :user_id");
            $stmtDelete->execute([
                ':id' => $publicationId,
                ':user_id' => $userId
            ]);

            return true;
        } catch (\Exception $e) {
            error_log('[APP ERROR] deletePublication: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllPublications(): array
    {
        try {
            $stmt = $this->request->prepare("
            SELECT p.id, p.user_id, p.image_path, p.sticker, p.x, p.y, 
                   p.likes, p.comments_count, p.created_at,
                   u.username, u.profile_picture
            FROM publications p
            JOIN users u ON u.id = p.user_id
            ORDER BY p.created_at DESC
            LIMIT 50
        ");
            $stmt->execute();
            $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($publications as &$publication) {
                $stmtComments = $this->request->prepare("
                SELECT c.content, c.created_at, u.username, u.profile_picture
                FROM comments c
                JOIN users u ON u.id = c.user_id
                WHERE c.publication_id = :publication_id
                ORDER BY c.created_at ASC
            ");
                $stmtComments->execute([':publication_id' => $publication['id']]);
                $publication['comments'] = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
            }
            return $publications;
        } catch (PDOException $e) {
            error_log('[PDO ERROR] getAllPublications : ' . $e->getMessage());
            return [];
        }
    }
}