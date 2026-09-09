<?php


namespace App\Exceptions;

use Exception;

class JsonException extends Exception
{
    private array $data;
    private int $status;

    public function __construct(int $status, array $data)
    {
        parent::__construct($data['message'][0] ?? 'Erreur');
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): array
    {
        return $this->data;
    }
}