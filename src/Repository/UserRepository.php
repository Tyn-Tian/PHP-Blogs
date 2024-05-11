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

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM users");
    }
}