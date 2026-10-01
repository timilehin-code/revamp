<?php

declare(strict_types=1);

namespace Models\Views;

use PDO;
use PDOException;
/**
 * blog post class to show all blogs to all visitors
 */
class Blogs
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all blog posts from the database ordered by newest first.
     *
     * 
     * @return array
     */
    public function getAllPosts(): array
    {
        try {
            $sql = "SELECT * FROM blog ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (function_exists('logProjectError')) {
                logProjectError("PDO Error in Blogs::getAllPosts(): " . $e->getMessage());
            }
            return [];
        }
    }
}
