<?php

namespace App\Helpers;

class Validator
{
    private static ?Validator $instance = null;

    private function __construct() {}

    public static function getInstance(): Validator
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function atLeast(string $value, int $minLength): bool
    {
        if (isset($value) && strlen($value) < $minLength) {
            return true;
        }
        return false;
    }



    private function validateSignup(array $data, array $errors): array
    {
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        $passwordsEmpty = (!empty($password) && !empty($confirmPassword));
        $passwordsMatch = ($password != $confirmPassword);

        if ($passwordsEmpty && $passwordsMatch) {
            $errors['password'] = "Les mots de passe ne correspondent pas.";
        }

        $passwordMinLength = 8;
        $usernameMinLength = 4;

        $passwordTooShort = $this->atLeast($password, $passwordMinLength);
        $usernameTooShort = $this->atLeast($username, $usernameMinLength);

        if ($passwordTooShort) {
            $errors['password'] = "Le mot de passe doit contenir au moins $passwordMinLength caractères.";
        }
        if ($usernameTooShort) {
            $errors['username'] = "Le nom d'utilisateur doit contenir au moins $usernameMinLength caractères.";
        }

        if (!empty($password)) {
            $hasLower = ($password !== strtoupper($password));
            $hasUpper = ($password !== strtolower($password));
            $hasDigit = strpbrk($password, '0123456789') !== false;

            if (!($hasLower && $hasUpper && $hasDigit)) {
                $errors['password'] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
            }
        }
        return $errors;
    }

    public function validateForgotPassword(string $newPassword, string $confirmPassword): array
    {
        $errors = [];

        if ($newPassword !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        $passwordMinLength = 8;

        if (strlen($newPassword) < $passwordMinLength) {
            $errors[] = "Le mot de passe doit contenir au moins $passwordMinLength caractères.";
        }

        $hasLower = ($newPassword !== strtoupper($newPassword));
        $hasUpper = ($newPassword !== strtolower($newPassword));
        $hasDigit = strpbrk($newPassword, '0123456789') !== false;

        if (!($hasLower && $hasUpper && $hasDigit)) {
            $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
        }

        return $errors;
    }



    public function getSignupFields(): array
    {
        return ["username", "email", "password", "confirm_password"];
    }

    public function getLoginFields(): array
    {
        return ["username_or_email", "password"];
    }

    public function responses(bool $success, array $message = []): array
    {
        return [
            'status'  => $success ? 'success' : 'errors',
            'message' => $message ?: ['Aucune erreur n\'a été détectée.']
        ];
    }


    public function protectKey(mixed $key, mixed $data): bool
    {
        if (!array_key_exists($key, $data))
            return (false);
        return (true);
    }

    public function signupValidation($data = []): array
    {
        $fieldsSignup = $this->getSignupFields();

        $signupValues = [];
        $errors = [];

        foreach ($fieldsSignup as $key) {
            $protect = $this->protectKey($key, $data);
            if (!$protect)
                return $this->responses(false, ['general' => "Le champ '$key' est manquant."]);

            $signupValues[$key] = $data[$key];
            $sanitized = $this->sanitize($signupValues[$key]);
            $isValid = $this->validateRequired($sanitized);

            if (!$isValid)
                return $this->responses(false, ['general' => "Le champ '$key' est vide."]);
            
            if ($key === 'email' && !$this->validateEmail($sanitized)) {
                return $this->responses(false, ['email' => "L'adresse email n'est pas valide."]);
            }
        }

        $errors = $this->validateSignup($signupValues, $errors);
        if (!empty($errors))
            return $this->responses(false, $errors);

        return $this->responses(true, ["message" => "Validation réussie"]);
    }


    public function loginValidation(array $data = []): array
    {
        $fieldsLogin = $this->getLoginFields();
        $loginValues = [];

        foreach ($fieldsLogin as $key) {
            $protect = $this->protectKey($key, $data);
            if (!$protect)
                return $this->responses(false, ['general' => "Le champ '$key' est manquant."]);

            $loginValues[$key] = $data[$key];
            $sanitized = $this->sanitize($loginValues[$key]);
            $isValid = $this->validateRequired($sanitized);
            if (!$isValid)
                return $this->responses(false, ['general' => "Le champ '$key' est vide."]);
        }

        return $this->responses(true, ["message" => "Validation réussie"]);
    }

    public function updateValidation(array $data = []): array
    {
        $errors = [];

        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        $passwordsEmpty = !empty($newPassword) || !empty($confirmPassword);
        if ($passwordsEmpty && $newPassword !== $confirmPassword) {
            $errors['new_password'] = "Les mots de passe ne correspondent pas.";
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, ["username", "email", "new_password", "confirm_password", "notify_comments"])) {
                continue;
            }

            if ($key === "confirm_password") continue;

            if ($key === "notify_comments") {
                $data[$key] = ($value == 1) ? 1 : 0;
                continue;
            }

            $sanitized = $this->sanitize($value);
            $validation = $this->validateRequired($sanitized);

            if (!$validation) {
                $errors[$key] = "Le champ $key est vide.";
                continue;
            }


            if ($key === "username" && $this->atLeast($sanitized, 4)) {
                $errors['username'] = "Le nom d'utilisateur doit contenir au moins 4 caractères.";
            }

            if ($key === "email" && !filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "L'adresse email n'est pas valide.";
            }

            if ($key === "new_password" && !empty($sanitized)) {
                if ($this->atLeast($sanitized, 8)) {
                    $errors['new_password'] = "Le mot de passe doit contenir au moins 8 caractères.";
                } else {
                    $hasLower = ($sanitized !== strtoupper($sanitized));
                    $hasUpper = ($sanitized !== strtolower($sanitized));
                    $hasDigit = strpbrk($sanitized, '0123456789') !== false;

                    if (!($hasLower && $hasUpper && $hasDigit)) {
                        $errors['new_password'] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
                    }
                }
            }
        }

        return !empty($errors)
            ? $this->responses(false, $errors)
            : $this->responses(true, $data);
    }


    public function getFile(string $key): ?array
    {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            return $_FILES[$key];
        }
        return null;
    }

    public function checkFile($key): array
    {
        $file = $this->getFile($key);
        if (!$file) {
            return $this->responses(false, ['Aucune photo n’a été sélectionnée.']);
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mimeContent = mime_content_type($file['tmp_name']);
        if (!in_array($mimeContent, $allowed_types)) {
            return $this->responses(false, ['Erreur : type de fichier invalide.']);
        }

        $size = @getimagesize($file['tmp_name']);
        if (!$size) {
            return $this->responses(false, ["Erreur : le fichier n'est pas une image réelle."]);
        }

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_extensions)) {
            return $this->responses(false, ["Extension invalide."]);
        }

        return $this->responses(true, []);
    }

    public function validateRequired(string $value): bool
    {
        return isset($value) && !empty($value);
    }

    public function sanitize($value): string
    {
        $removeWhitespace = trim($value);
        return htmlspecialchars($removeWhitespace, ENT_QUOTES, 'UTF-8');
    }

    public function validateEmail(mixed $value): bool
    {
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
