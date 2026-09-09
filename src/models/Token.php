<?php

namespace App\Models;

use App\Helpers\Database;
use PDO;

class Token
{
    private static ?self $instance = null;
    private PDO $request;

    private function __construct()
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


    public function setEmailConfirmationToken(int $userId, string $token): bool
    {
        $stmt = $this->request->prepare("UPDATE users SET email_confirmation_token = :token WHERE id = :id");
        return $stmt->execute(['token' => $token, 'id' => $userId]);
    }

    public function findByToken(string $token): ?array
    {
        $stmt = $this->request->prepare("SELECT * FROM users WHERE email_confirmation_token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function confirmEmail(int $userId): bool
    {
        $stmt = $this->request->prepare("UPDATE users SET is_email_confirmed = true, email_confirmation_token = NULL WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }


    public function setResetPasswordToken(int $userId, string $token): bool
    {
        try {
            $stmt = $this->request->prepare("
            UPDATE users 
            SET reset_password_token = :token,
                updated_at = NOW()
            WHERE id = :id
        ");
            return $stmt->execute([
                ':token' => $token,
                ':id' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur setResetPasswordToken: " . $e->getMessage());
            return false;
        }
    }


    public function findByResetToken(string $token): ?array
    {
        try {
            $stmt = $this->request->prepare("
            SELECT * FROM users WHERE reset_password_token = :token
        ");
            $stmt->execute([':token' => $token]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByResetToken: " . $e->getMessage());
            return null;
        }
    }

    public function findByResetPasswordToken(string $token): ?array
    {
        try {
            $sql = "SELECT * FROM users WHERE reset_password_token = :token LIMIT 1";
            $stmt = $this->request->prepare($sql);
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByResetPasswordToken: " . $e->getMessage());
            return null;
        }
    }

    public function clearResetPasswordToken(int $userId): bool
    {
        try {
            $sql = "UPDATE users SET reset_password_token = NULL, reset_password_expires_at = NULL WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([':id' => $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur clearResetPasswordToken: " . $e->getMessage());
            return false;
        }
    }
}