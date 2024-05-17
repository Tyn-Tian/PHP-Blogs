<?php

namespace Blog\Repository;

use Blog\Domain\User;

class UserRepository
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users(id, email, username, password) VALUES(?, ?, ?, ?)");
        $statement->execute([
            $user->id,
            $user->email,
            $user->username,
            $user->password
        ]);
        return $user;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT id, email, username, password FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->email = $row['email'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByEmail(string $email): ?User
    {   
        $statement = $this->connection->prepare("SELECT id, email, username, password FROM users WHERE email = ?");
        $statement->execute([$email]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->email = $row['email'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM users");
    }

    public function findAllBlog(string $userId)
    {
        $statement = $this->connection->prepare("SELECT users.username, blogs.id, blogs.title, blogs.content, blogs.created_at FROM users JOIN blogs on (users.id = blogs.user_id) WHERE users.id = ? ORDER BY blogs.created_at DESC");
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
}
