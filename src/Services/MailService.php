<?php

namespace App\Services;

class MailService
{
    private string $from = 'camagru@yasaidi.42.fr';

    public function sendEmailConfirmation(string $to, string $token, string $baseUrl)
    {
        $subject = "Confirmez votre email";
        $message = "Cliquez sur ce lien pour confirmer votre email : ";
        $message .= "{$baseUrl}/confirm-email?token={$token}";
            
        return mail($to, $subject, $message, "From: {$this->from}");
    }

    public function sendResetPasswordEmail(string $to, string $token, string $baseUrl)
    {
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : ";
        $message .= "{$baseUrl}/reset-password?token={$token}";
  
        return mail($to, $subject, $message, "From: {$this->from}");
    }

    public function sendCommentNotification(string $to, string $commentAuthor)
    {
        $subject = "Nouvelle notification de commentaire";
        $message = "Bonjour,\n\n";
        $message .= "{$commentAuthor} a commenté votre publication.\n";
        $message .= "Cordialement,\nL'équipe Camagru";

        return mail($to, $subject, $message, "From: {$this->from}");
    }
}