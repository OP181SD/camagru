<?php



namespace App\Controllers;

use App\Helpers\View;
use App\Services\AuthService;
use App\Models\Publication;

class PageController
{
    private View $view;
    private AuthService $auth;
    private Publication $publication;

    public function __construct()
    {
        $this->view = View::getInstance();
        $this->view->setLayout('main');
        $this->auth = AuthService::getInstance();
        $this->publication = Publication::getInstance();
    }

    public function home(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }

        $this->view->renderLayout('Pages/home', [
            'title' => 'Camagru'
        ]);
    }

    public function login(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }

        $this->view->renderLayout('Pages/login', [
            'title' => 'Connexion'
        ]);
    }

    public function signup(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }

        $this->view->renderLayout('Pages/signup', [
            'title' => 'Inscription'
        ]);
    }

    public function notFound(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }

        $this->view->renderLayout('Pages/not-found', [
            'title' => '404'
        ]);
    }

    public function forgotPassword(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }

        $this->view->renderLayout('Pages/forgot-password', [
            'title' => 'Mot de passe oublié'
        ]);
    }

    public function signupStepTwo(): void
    {
        $this->view->renderLayout('Pages/signup-step-two', [
            'title' => 'Étape 2'
        ]);
    }

    public function signupSuccess(): void
    {
        $this->view->renderLayout('Pages/signup-success', [
            'title' => 'Inscription réussie'
        ]);
    }

    public function verifyEmail(): void
    {
        $this->view->renderLayout('Pages/verify-email', [
            'title' => 'Vérification de l’email'
        ]);
    }

    public function resetPassword(): void
    {
        if ($this->auth->isLogged()) {
            $this->auth->redirect('/feed');
            return;
        }
        $this->view->renderLayout('Pages/reset-password', [
            'title' => 'Réinitialisation du mot de passe'
        ]);
    }

    public function passwordChanged(): void
    {
        $this->view->renderLayout('Pages/password-changed', [
            'title' => 'Mot de passe modifié'
        ]);
    }

    public function feed(): void
    {
        if (!$this->auth->isLogged()) {
            $this->auth->redirect('/login');
            return;
        }

        $publications = $this->publication->getAllPublications();

        $this->view->renderLayout('Pages/feed', [
            'title' => 'Feed',
            'publications' => $publications
        ]);
    }

    public function gallery(): void
    {
        $publications = $this->publication->getAllPublications();

        $this->view->renderLayout('Pages/gallery', [
            'title' => 'Gallery',
            'publications' => $publications
        ]);
    }

    public function studio(): void
    {
        if (!$this->auth->isLogged()) {
            $this->auth->redirect('/login');
            return;
        }

        $this->view->renderLayout('Pages/studio', [
            'title' => 'Studio'
        ]);
    }

    public function settings(): void
    {
        if (!$this->auth->isLogged()) {
            $this->auth->redirect('/login');
            return;
        }

        $this->view->renderLayout('Pages/settings', [
            'title' => 'Paramètres'
        ]);
    }
}
