<?php

namespace App\Models\Auth;

use App\Helpers\Database;
use PDO;


class Register
{
    private PDO $request;

    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }


    public function getExistingUserId(array $data): ?int
    {
        try {
            $sql = "SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1";
            $stmt = $this->request->prepare($sql);
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
            ]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row != false)
                return (int)$row['id'];
            else
                return null;
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'insertion utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function insert(array $data): ?int
    {
        try {
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $this->request->prepare($sql);
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);

            $insertID = (int)$this->request->lastInsertId();
            if ($insertID > 0)
                return $insertID;
            else
                return null;
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'insertion utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public function customDirectory(): string
    {
        $uploadDir = __DIR__ . '/../../public/assets/uploads/profile_pictures/';
        if (!is_dir($uploadDir)) {
            throw new \Exception("Le dossier $uploadDir n'existe pas !");
        }
        return $uploadDir;
    }


    public function generateNameFile(array $file): string
    {
        $fileName = $file['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        return uniqid('profile_', true) . '.' . $ext;
    }

    public function updateProfilPicture(array $data): bool
    {
        try {
            $uploadDir = $this->customDirectory();
            $fileName = $this->generateNameFile($data['profile_picture']);
            $targetPath = $uploadDir . $fileName;

            if (!move_uploaded_file($data['profile_picture']['tmp_name'], $targetPath)) {
                return false;
            }

            $filePath = '/assets/uploads/profile_pictures/' . $fileName;

            $sql = "UPDATE users 
                    SET profile_picture = :profile_picture,
                        updated_at = NOW()
                    WHERE id = :user_id";

            $stmt = $this->request->prepare($sql);
            return $stmt->execute([
                ':profile_picture' => $filePath,
                ':user_id' => $data['user_id'],
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur updateProfilPicture: " . $e->getMessage());
            return false;
        }
    }

    public function setEmailConfirmationToken(int $userId, string $token): bool
    {
        $stmt = $this->request->prepare("UPDATE users SET email_confirmation_token = :token WHERE id = :id");
        return $stmt->execute(['token' => $token, 'id' => $userId]);
    }

    public function FindRequestById(int $id): ?array
    {
        try {
            $sql = "SELECT id, username, email, profile_picture, is_email_confirmed, created_at 
                    FROM users WHERE id = :id";

            $stmt = $this->request->prepare($sql);
            $stmt->execute([':id' => $id]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur finrequestyId: " . $e->getMessage());
            return null;
        }
    }

    public function deleteById(int $userId): bool
    {
        try {
            $stmt = $this->request->prepare("DELETE FROM users WHERE id = :user_id");
            return $stmt->execute([':user_id' => $userId]);
        } catch (\PDOException $e) {
            error_log('Erreur deleteById: ' . $e->getMessage());
            return false;
        }
    }


    public function findByEmail(string $email): ?array
    {
        try {
            $stmt = $this->request->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByEmail: " . $e->getMessage());
            return null;
        }
    }

    public function setResetPasswordToken(int $userId, string $token, string $expiresAt): bool
    {
        $sql = "UPDATE users 
            SET reset_password_token = :token, 
                reset_password_expires = :expires 
            WHERE id = :id";
        $stmt = $this->request->prepare($sql);
        return $stmt->execute([
            ':token' => $token,
            ':expires' => $expiresAt,
            ':id' => $userId
        ]);
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
            $sql = "UPDATE users 
                SET reset_password_token = NULL, 
                    reset_password_expires = NULL 
                WHERE id = :id";
            $stmt = $this->request->prepare($sql);
            return $stmt->execute([':id' => $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur clearResetPasswordToken: " . $e->getMessage());
            return false;
        }
    }

    public function clearExpiredResetPasswordToken(): bool
    {
        try {
            $sql = "UPDATE users
                SET reset_password_token = NULL,
                    reset_password_expires = NULL
                WHERE reset_password_expires <= NOW()";
            return $this->request->exec($sql) !== false;
        } catch (\PDOException $e) {
            error_log("Erreur clearExpiredResetPasswordToken: " . $e->getMessage());
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
}
