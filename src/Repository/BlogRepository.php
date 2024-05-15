<?php

namespace Blog\Repository;

use Blog\Domain\Blog;

class BlogRepository
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    public function save(Blog $blog): Blog
    {
        $statement = $this->connection->prepare("INSERT INTO blogs(id, title, content, user_id) VALUES(?, ?, ?, ?)");
        $statement->execute([
            $blog->id,
            $blog->title,
            $blog->content,
            $blog->userId
        ]);
        return $blog;
    }

    public function findById(string $id): ?Blog
    {
        $statement = $this->connection->prepare("SELECT id, title, content, created_at, user_id FROM blogs WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $blog = new Blog();
                $blog->id = $row['id'];
                $blog->title = $row['title'];
                $blog->content = $row['content'];
                $blog->createdAt = $row['created_at'];
                $blog->userId = $row['user_id'];
                return $blog;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM blogs");
    }
}