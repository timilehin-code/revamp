<?php

declare(strict_types=1);

namespace Models\Admin;

use PDO;
use PDOException;

class Authentication
{
    public string $name;
    public string $pswd;
    public PDO $conn;

    public function __construct(string $name, string $pswd, PDO $conn)
    {
        $this->name = $name;
        $this->pswd = $pswd;
        $this->conn = $conn;
    }

    /**
     * Register a new admin user
     */
    public function register(): bool
    {
        try {
            // 1. Check if username already exists in the 'user' table
            $checkSql = "SELECT id FROM user WHERE userName = :name LIMIT 1";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([':name' => $this->name]);

            if ($checkStmt->fetch()) {
                if (function_exists('logProjectError')) {
                    logProjectError("Registration Error: Username '{$this->name}' already exists.");
                }
                return false;
            }

            $hashedPassword = password_hash($this->pswd, PASSWORD_ARGON2ID);


            $sql = "INSERT INTO user (userName, password, created_at) VALUES (:name, :pswd, NOW())";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':name' => $this->name,
                ':pswd' => $hashedPassword,
            ]);
        } catch (PDOException $e) {
            if (function_exists('logProjectError')) {
                logProjectError("PDO Error in Authentication::register(): " . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Authenticate and log in an admin user
     * 
     * @return array|false Returns user array on success, false on failure
     */
    public function login()
    {
        try {
            // 1. Fetch user record by username
            $sql = "SELECT * FROM user WHERE username = :name LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':name' => $this->name]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($this->pswd, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                session_regenerate_id(true);

                // Set session flags
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $user['id'];
                $_SESSION['admin_username']  = $user['username'];
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            if (function_exists('logProjectError')) {
                logProjectError("PDO Error in Authentication::login(): " . $e->getMessage());
            }
            return false;
        }
    }
}
