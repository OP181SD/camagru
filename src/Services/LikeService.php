<?php

namespace App\Services;

use App\Models\Like;

class LikeService
{
    private Like $likeModel;

    public function __construct()
    {
        $this->likeModel = new Like();
    }

    public function toggleLike(int $userId, int $publicationId, bool $liked): array
    {
        if ($userId <= 0 || $publicationId <= 0) {
            return [
                'status' => 'error',
                'message' => 'Paramètres invalides.'
            ];
        }

        try {
            if ($liked) {
                $likes = $this->likeModel->like($userId, $publicationId);
            } else {
                $likes = $this->likeModel->unlike($userId, $publicationId);
            }

            return [
                'status' => 'success',
                'likes' => $likes
            ];
        } catch (\Exception $e) {
            error_log('[APP ERROR] LikeService::toggleLike - ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Erreur serveur.'
            ];
        }
    }

    public function getLikeStatus(int $userId, int $publicationId): array
    {
        try {
            $liked = $this->likeModel->hasLiked($userId, $publicationId);
            $likesCount = $this->likeModel->getLikesCount($publicationId);

            return [
                'status' => 'success',
                'likes' => $likesCount,
                'liked' => $liked
            ];
        } catch (\Exception $e) {
            error_log('[APP ERROR] LikeService::getLikeStatus - ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Erreur serveur.'
            ];
        }
    }
}
