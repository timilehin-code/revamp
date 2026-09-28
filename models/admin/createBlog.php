<?php

declare(strict_types=1);

namespace Models\admin;


use PDO;
use PDOException;

class CreateBlog
{
    public string $title;
    public string $slug;
    public string $excerpt;

    public string $content;

    public array $tags;

    public pdo $conn;
    public function __construct(string $title, string $slug, string $excerpt, string $content, array $tags, PDO $conn)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->excerpt = $excerpt;
        $this->content = $content;
        $this->tags = $tags;
        $this->conn = $conn;
    }

    private function create()
    {
        try {
            $sql = "INSERT INTO blog(title,slug,excerpt,content,tags,created_at,updated_at,published_at) VALUE (:title,:slug,:excerpt,:content,:tags, NOW(),NOW(),NOW()) ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":slug", $this->slug);
            $stmt->bindParam(":excerpt", $this->excerpt);
            $stmt->bindParam(":content", $this->content);
            $stmt->bindParam(":tag", $this->tags);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error posting: " . $e->getMessage());
            return false;
        }
    }
    public function getCreate()
    {
        $this->create();
    }
}
