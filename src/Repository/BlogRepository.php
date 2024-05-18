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

    public function findAllBlogExceptCurrentUser($userId)
    {
        $statement = $this->connection->prepare("SELECT blogs.id, blogs.title, blogs.content, blogs.created_at, users.username FROM blogs JOIN users on (blogs.user_id = users.id) WHERE users.id != ? ORDER BY blogs.created_at DESC");
        $statement->execute([$userId]);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
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