<?php

class Database
{
    private static $instance = null;

    private $host;
    private $dbname;
    private $user;
    private $password;
    private $dsn;

    private $pdo;

    private function __construct() {
        $this->host = "db";
        $this->dbname = "camagru";
        $this->user = "user";
        $this->password = "password";
        $this->dsn = "pgsql:host={$this->host};dbname={$this->dbname}"; 

        try {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password);
            echo "Connexion réussie";
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Throwable $th) {

            echo "Error: " . $th->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

$db = Database::getInstance();

?>
