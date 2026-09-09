<?php

namespace App\Models;

use App\Helpers\Database;
use PDO;

class Update
{
    private PDO $request;
    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }

    public function updateUsername(int $userID, string $username): bool
    {
        try {
            $sql = "UPDATE users SET username = :username WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([
                ':username' => $username,
                ':id' => $userID
            ]);
        } catch (\PDOException $e) {
            error_log('Erreur at UpdateUsername: ' . $e->getMessage());
            return false;
        }
    }

    public function updateEmail(int $userID, string $email): bool
    {
        try {
            $sql = "UPDATE users SET email = :email WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([
                ':email' => $email,
                ':id' => $userID
            ]);
        } catch (\PDOException $e) {
            error_log('Erreur at UpdateEmail: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePassword(int $userID, string $password): bool
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([
                ':password' => $hashedPassword,
                ':id' => $userID
            ]);
        } catch (\PDOException $e) {
            error_log('Erreur at UpdatePassword: ' . $e->getMessage());
            return false;
        }
    }

    public function updateNotifyComments(int $userID, bool $notify): bool
    {
        try {
            $sql = "UPDATE users SET notify_comments = :notify WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([
                ':notify' => $notify ? 1 : 0,
                ':id' => $userID
            ]);
        } catch (\PDOException $e) {
            error_log('Erreur at UpdateNotifyComments: ' . $e->getMessage());
            return false;
        }
    }
}
