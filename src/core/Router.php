<?php



namespace App\Core;

class Router
{
    private static ?Router $instance = null;
    private array $static_routes = [];
    private array $dynamic_routes = [];

    private function __construct() {}

    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function addStaticRoute(string $path, callable $callback): void
    {
        $this->static_routes[$path] = $callback;
    }

    public function addDynamicRoute(string $method, string $path, callable $callback): void
    {
        $this->dynamic_routes[$method][$path] = $callback;
    }

    private function getPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $path ?: '';
    }

    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function run(): void
    {
        $path = $this->getPath();
        $method = $this->getMethod();

        if (isset($this->static_routes[$path])) {
            ($this->static_routes[$path])();
            return;
        }

        if (isset($this->dynamic_routes[$method][$path])) {
            ($this->dynamic_routes[$method][$path])();
            return;
        }
        $controller = new \App\Controllers\PageController();
        $controller->notFound();
    }
}