<?php

declare(strict_types=1);

namespace Models\Conn;

use PDO;
use PDOException;

class Conn
{
    private string $host;
    private string $user;
    private string $password;
    private string $dbName;
    private string $charset;
    private ?PDO $pdo = null; // Fixed: Changed from mysqli to ?PDO

    public function __construct(
        string $host,
        string $user,
        string $password,
        string $dbName,
        string $charset = 'utf8mb4'
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbName = $dbName;
        $this->charset = $charset;
    }

    private function setConnect(): PDO
    {
        // If connection already exists, reuse it
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    public function getConnect(): PDO
    {
        return $this->setConnect();
    }
}
