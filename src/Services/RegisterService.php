<?php

namespace App\Services;

use App\Helpers\Validator;
use App\Services\MailService;
use App\Models\Auth\Register;

class RegisterService
{
    private Validator $validator;
    private MailService $mailService;
    private Register $request;

    public function __construct()
    {
        $this->validator = Validator::getInstance();
        $this->mailService = new MailService();
        $this->request = new Register();
    }

    public function registerStepOne(array $data): array
    {
        $validation = $this->validator->signupValidation($data);
        if ($validation['status'] === "errors") {
            return $validation;
        }

        if ($this->request->getExistingUserId($data) !== null) {
            return $this->validator->responses(false, ['Cet utilisateur existe déjà.']);
        }

        $insertID = $this->request->insert($data);
        if (!$insertID) {
            return $this->validator->responses(false, ['Impossible de créer l’utilisateur']);
        }

        return [
            'status' => 'success',
            'message' => 'Utilisateur créé avec succès.',
            'data' => [
                'user_id' => $insertID,
                'url' => '/signup-step-two'
            ]
        ];
    }

    public function registerStepTwo(int $userId): array
    {
        $validation = $this->validator->checkFile('profile_picture');
        if ($validation['status'] === 'errors') {
            return $validation;
        }

        $file = $_FILES['profile_picture'];
        $success = $this->request->updateProfilPicture([
            'user_id' => $userId,
            'profile_picture' => $file
        ]);

        if (!$success) {
            return $this->validator->responses(false, ['Impossible de mettre à jour la photo de profil']);
        }

        $token = bin2hex(random_bytes(16));
        $this->request->setEmailConfirmationToken($userId, $token);

        $user = $this->request->FindRequestById($userId);
        if (!$user || empty($user['email'])) {
            return $this->validator->responses(false, ['Utilisateur introuvable ou email manquant.']);
        }

        $this->mailService->sendEmailConfirmation($user['email'], $token, BASE_URL);

        return $this->validator->responses(true, ['Photo de profil mise à jour et email envoyé.']);
    }

    public function cancelPartialSignupService(int $userId): void
    {
        if ($userId) {
            $this->request->deleteById($userId);
        }
    }

    public function sendAnEmail(string $email): array
    {
        $user = $this->request->findByEmail($email);
        if (!$user) {
            return $this->validator->responses(false, ['Cette adresse email n’existe pas.']);
        }

        $token = bin2hex(random_bytes(16));
        $expiresAt = gmdate('Y-m-d H:i:s', time() + 45);

        $this->request->setResetPasswordToken($user['id'], $token, $expiresAt);
        $this->mailService->sendResetPasswordEmail($email, $token, BASE_URL);

        return $this->validator->responses(true, ['Si l’adresse existe, un email de réinitialisation a été envoyé.']);
    }

    public function changeForgotPassword(string $token, string $newPassword, string $confirmPassword): array
    {
        $this->request->clearExpiredResetPasswordToken();

        $user = $this->request->findByResetPasswordToken($token);
        if (!$user) {
            return $this->validator->responses(false, ['Votre lien a expiré, veuillez réessayer']);
        }

        $errors = $this->validator->validateForgotPassword($newPassword, $confirmPassword);

        if (!empty($errors)) {
            return $this->validator->responses(false, $errors);
        }

        $this->request->updatePassword($user['id'], $newPassword);
        $this->request->clearResetPasswordToken($user['id']);

        return $this->validator->responses(true, ['Mot de passe mis à jour avec succès.']);
    }
}
