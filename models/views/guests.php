<?php

declare(strict_types=1);

namespace Models\Views;

use PDO;
use PDOException;

/**
 *class  to show all guests to all visitors
 */
class Guests
{
    public string $name;
    public string $note;
    public string $signature;
    public PDO $connection;
    /**
     * Constructor for the Guests class.
     *
     * @param string $name The name of the guest.
     * @param string $note A short note from the guest.
     * @param string $signature The signature of the guest.
     * @param PDO $connection The PDO database connection.
     */
    public function __construct(string $name, string $note, string $signature, PDO $connection)
    {
        $this->name = $name;
        $this->note = $note;
        $this->signature = $signature;
        $this->connection = $connection;
    }

    public function saveGuest(): bool
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO guests (name, note, signature, created_at) VALUES (:name, :note, :signature, NOW())");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':note', $this->note);
            $stmt->bindParam(':signature', $this->signature);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
}
