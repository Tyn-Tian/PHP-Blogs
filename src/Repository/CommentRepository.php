<?php

namespace Blog\Repository;

use Blog\Domain\Comment;

class CommentRepository
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    public function save(Comment $comment): Comment
    {
        $statement = $this->connection->prepare("INSERT INTO comments(id, content, user_id, blog_id) VALUES(?, ?, ?, ?)");
        $statement->execute([
            $comment->id,
            $comment->content,
            $comment->userId,
            $comment->blogId
        ]);
        return $comment;
    }

    public function findById(string $commentId): ?Comment
    {
        $statement = $this->connection->prepare("SELECT id, content, created_at, user_id, blog_id FROM comments WHERE id = ?");
        $statement->execute([$commentId]);

        try {
            if ($row = $statement->fetch()) {
                $comment = new Comment();
                $comment->id = $row['id'];
                $comment->content = $row['content'];
                $comment->createdAt = $row['created_at'];
                $comment->userId = $row['user_id'];
                $comment->blogId = $row['blog_id'];
                return $comment;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll()
    {
        $this->connection->exec("DELETE FROM comments");
    }

    public function deleteById(string $commentId)
    {
        $statement = $this->connection->prepare("DELETE FROM comments WHERE id = ?");
        $statement->execute([$commentId]);
    }
}
