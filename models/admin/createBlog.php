<?php

declare(strict_types=1);

namespace Models\Admin;

use PDO;
use PDOException;

class CreateBlog
{
    public string $title;
    public string $slug;
    public string $excerpt;
    public string $content;
    public string $tags;

    public string $coverImage;
    public PDO $conn;

    public function __construct(
        string $title,
        string $slug,
        string $excerpt,
        string $content,
        string $tags,
        string $coverImage,
        PDO $conn
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->excerpt = $excerpt;
        $this->content = $content;
        $this->tags = $tags;
        $this->coverImage = $coverImage;
        $this->conn = $conn;
    }

    public function create(): bool
    {
        try {
            $sql = "INSERT INTO blog (title, slug, excerpt, content, tags,cover_image, created_at, updated_at, published_at) 
                    VALUES (:title, :slug, :excerpt, :content, :tags,:cover_image NOW(), NOW(), NOW())";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':title'   => $this->title,
                ':slug'    => $this->slug,
                ':excerpt' => $this->excerpt,
                ':content' => $this->content,
                ':tags'    => $this->tags,
                ':cover_image' => $this->coverImage
            ]);
        } catch (PDOException $e) {
            // Write exact MySQL exception to project log
            logProjectError("PDO Error in CreateBlog::create(): " . $e->getMessage());
            return false;
        }
    }
}
