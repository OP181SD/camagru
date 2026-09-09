<?php

namespace App\Services;

use App\Models\Comment;
use App\Services\MailService;

class CommentService
{
    private Comment $commentModel;
    private MailService $mailService;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->mailService = new MailService();
    }
    public function addComment(int $publicationId, int $userId, string $content): array
    {
        $comment = $this->commentModel->commentaryInsert($publicationId, $userId, $content);

        $commenter = $this->commentModel->getUserById($userId);
        $publicationOwner = $this->getPublicationOwner($publicationId);

        if (
            $publicationOwner &&
            isset($publicationOwner['email'], $publicationOwner['notify_comments']) &&
            $publicationOwner['id'] != $userId &&
            (bool)$publicationOwner['notify_comments'] 
        ) {
            $publicationUrl = BASE_URL . "/publication/{$publicationId}";

            $this->mailService->sendCommentNotification(
                $publicationOwner['email'],
                $commenter['username'],
                $publicationUrl
            );
        }

        return $comment;
    }


    public function getPublicationOwner(int $publicationId): ?array
    {
        return $this->commentModel->getPublicationOwner($publicationId);
    }
}
