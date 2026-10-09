<?php

declare(strict_types=1);

namespace Models\Views;

use PDO;
use PDOException;

class ViewGuests{
    
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllGuests(): array
    {
        try {
            $sql = "SELECT * FROM guests ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (function_exists('logProjectError')) {
                logProjectError("PDO Error in ViewGuests::getAllGuests(): " . $e->getMessage());
            }
            return [];
        }
    }
}