<?php

namespace App\Services;

use App\Helpers\Validator;
use App\Models\Auth\Login;

class LoginService
{
    private Login $request;
    private Validator $validator;

    public function __construct()
    {
        $this->validator = Validator::getInstance();
        $this->request = new Login();
    }


    public function login(array $data): array
    {
        $validation = $this->validator->loginValidation($data);
        if ($validation['status'] === 'errors') {
            return $validation;
        }
        $user = $this->request->FindRequestByUsernameOrEmail($data['username_or_email']);
        if (!$user) {
            return $this->validator->responses(false, ['general' => 'Utilisateur introuvable.']);
        }
        
        if (!password_verify($data['password'], $user['password'])) {
            return $this->validator->responses(false, ['general' => 'Mot de passe incorrect.']);
        }

        if (!$user['is_email_confirmed']) {
            return $this->validator->responses(false, ['general' => 'Veuillez confirmer votre adresse email avant de vous connecter.']);
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'profile_picture' => $user['profile_picture'] ?? null
        ];
        return $this->validator->responses(true, ['message' => 'Connexion réussie.']);
    }
}