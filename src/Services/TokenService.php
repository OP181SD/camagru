<?php

namespace App\Services;

use App\Models\Token;
use App\Services\AuthService;

class TokenService
{
    private Token $request;
    private AuthService $services;
    

    public function __construct()
    {
        $this->request = Token::getInstance();
        $this->services = AuthService::getInstance();
    }

    public function confirmEmail(): void
    {
        $token = $_GET['token'] ?? '';   
        $user = $this->request->findByToken($token);
        if ($user) {
            $this->request->confirmEmail($user['id']);
            $this->services->redirect('/signup-success');
        } else {
            $this->services->redirect('/login');
        }
    }
}