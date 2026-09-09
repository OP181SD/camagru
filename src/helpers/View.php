<?php

namespace App\Helpers;

class View
{
    private string $basePath;
    private ?string $layout = null;
    private static ?self $instance = null;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public static function getInstance(string $basePath = 'src/Views'): self
    {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = basename($layout);
    }

    public function render(string $view, array $data = []): void
    {
        $view_path = $this->basePath . '/' . $view . '.php';

        if (!file_exists($view_path)) {
            throw new \Exception("La vue '$view' est introuvable à l'emplacement '$view_path'.");
        }
        extract($data);
        require $view_path;
    }


    public function renderPartial(string $partial, array $data = []): void
    {
        $partial = basename($partial);
        $partial_path = $this->basePath . "/partials/" . $partial . ".php"; 

        if (!file_exists($partial_path)) {
            throw new \Exception("Le partial '$partial' est introuvable à l'emplacement '$partial_path'.");
        }
        extract($data);
        require $partial_path;
    }

    public function renderLayout(string $view, array $data = []): void
    {
        if (!$this->layout) {
            throw new \Exception("Aucun layout n'a été défini. Utilisez setLayout() avant renderLayout().");
        }

        $layout_path = $this->basePath . "/layouts/" . $this->layout . ".php";
        if (!file_exists($layout_path)) {
            throw new \Exception("Le layout '{$this->layout}' est introuvable à l'emplacement '$layout_path'.");
        }

        ob_start();
        $this->render($view, $data);
        $content = ob_get_clean();
        extract($data);
        require $layout_path;
    }
}
