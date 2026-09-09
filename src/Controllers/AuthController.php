<?php

namespace App\Controllers;

use App\Core\Application;
use App\Services\RegisterService;
use App\Services\LoginService;

class AuthController
{
    private Application $response;
    private RegisterService $register;
    private LoginService $auth;

    public function __construct()
    {
        $this->response = Application::getInstance();
        $this->register = new RegisterService();
        $this->auth = new LoginService();
    }

    public function register(): void
    {
        $data = $this->response->jsonToArray();
        $result = $this->register->registerStepOne($data);
        $this->response->jsonResponse($result, $result['status'] === 'success' ? 200 : 0);
    }

    public function registerTwo(): void
    {
        if (!isset($_POST['user_id']) || !isset($_FILES['profile_picture'])) {
            $this->response->jsonResponse([
                'status' => 'errors',
                'message' => ['Données manquantes']
            ], 400);
            return;
        }

        $userId = (int) $_POST['user_id'];
        $result = $this->register->registerStepTwo($userId);
        $this->response->jsonResponse($result, $result['status'] === 'success' ? 200 : 0);
    }

    public function cancelPartialSignup(): void
    {
        $userId = $_POST['user_id'] ?? null;
        if ($userId) {
            $this->register->cancelPartialSignupService((int)$userId);
            unset($_SESSION['user']);
        }
    }

    public function loginAuth(): void
    {
        $data = $this->response->jsonToArray();
        $result = $this->auth->login($data);
        $this->response->jsonResponse($result, $result['status'] === 'success');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function forgotPasswordSendMail(): void
    {
        $data = $this->response->jsonToArray();
        $email = trim($data['email'] ?? '');
        $result = $this->register->sendAnEmail($email);
        $this->response->jsonResponse($result, $result['status'] === 'success' ? 200 : 0);
    }

    public function changeForgotPassword(): void
    {
        $data = $this->response->jsonToArray();
        $token = $data['token'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        $result = $this->register->changeForgotPassword($token, $newPassword, $confirmPassword);
        $this->response->jsonResponse($result, $result['status'] === 'success' ? 200 : 0);
    }
}
