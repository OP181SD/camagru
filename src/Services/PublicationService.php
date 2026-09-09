<?php

namespace App\Services;

use App\Helpers\Validator;
use App\Models\Publication;

class PublicationService
{
    private Publication $request;
    private Validator $validator;

    public function __construct()
    {
        $this->validator = Validator::getInstance();
        $this->request = new Publication();
    }

    public function createPublicationSticker(array $data): array
    {
        if (!isset($_SESSION['user']['id'])) {
            return $this->validator->responses(false, [
                'general' => 'Utilisateur non authentifié.'
            ]);
        }

        if (empty($data['photo'])) {
            return $this->validator->responses(false, [
                'photo' => 'Photo manquante.'
            ]);
        }

        $userId  = (int) $_SESSION['user']['id'];

        $picture = $data['photo'];
        $sticker = $data['sticker'] ?? null;
        $x       = isset($data['x']) ? (int) $data['x'] : 0;
        $y       = isset($data['y']) ? (int) $data['y'] : 0;

        $publicationId = $this->request->createPublication(
            $userId,
            $picture,
            $sticker,
            $x,
            $y,
            []
        );

        if (!$publicationId) {
            return $this->validator->responses(false, [
                'general' => 'Erreur lors de la création de la publication.'
            ]);
        }
        return [
            'status' => 'success',
            'message' => 'Publication créée avec succès',
            'publication_id' => $publicationId
        ];
    }


    public function createPublicationPictures(int $userId, array $data): array
    {
        if (!isset($_SESSION['user']['id'])) {
            return $this->validator->responses(false, [
                'general' => 'Utilisateur non authentifié.'
            ]);
        }

        $userId = (int)$_SESSION['user']['id'];

        $result = $this->validator->checkFile('image');
        if ($result['status'] === 'errors') {
            return $result;
        }

        $picture = $_FILES['image'] ?? null;
        if (!$picture) {
            return $this->validator->responses(false, [
                'image' => 'Aucune image uploadée.'
            ]);
        }
        $x = isset($data['x']) ? (int)$data['x'] : 0;
        $y = isset($data['y']) ? (int)$data['y'] : 0;

        $publicationId = $this->request->createPublication($userId, $picture['tmp_name'], '', $x, $y, $picture);

        if (!$publicationId) {
            return $this->validator->responses(false, ['Erreur lors de la création de la publication.']);
        }
        return [
            'status' => 'success',
            'message' => 'Publication créée avec succès',
            'publication_id' => $publicationId
        ];
    }


    public function deletePublicationService(int $publicationId, int $userId): array
    {
        if (!isset($_SESSION['user']['id'])) {
            return $this->validator->responses(false, [
                'general' => 'Utilisateur non authentifié.'
            ]);
        }

        $userId = (int) $_SESSION['user']['id'];
    
        $this->request->deletePublication($publicationId, $userId);

        return [
            'status' => 'success',
            'message' => 'Publication supprimée avec succès.'
        ];
    }
}
