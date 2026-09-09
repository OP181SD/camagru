<?php


namespace App\Models\Auth;

use App\Helpers\Database;
use PDO;


class Login
{
    private PDO $request;

    public function __construct()
    {
        $this->request = Database::getInstance()->getConnection();
    }

    public function FindRequestByUsernameOrEmail(string $identifier): ?array
    {
        try {
            $sql = "SELECT id, username, email, password, profile_picture, is_email_confirmed
                FROM users 
                WHERE username = :identifier OR email = :identifier
                LIMIT 1";

            $stmt = $this->request->prepare($sql);
            $stmt->execute([':identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur finrequestyUsernameOrEmail: " . $e->getMessage());
            return null;
        }   
    }
}