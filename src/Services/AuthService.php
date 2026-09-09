<?php

namespace App\Services;

class AuthService
{
    private static ?self $instance = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function launchSession(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
    }

    public function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: $url");
        return;
    }

    public function isLogged(): bool
    {
        if (!empty($_SESSION['user']))
            return (true);
        return (false);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}