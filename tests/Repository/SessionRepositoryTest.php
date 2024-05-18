<?php

namespace Blog\Repository;

use Blog\Config\Database;
use Blog\Domain\Session;
use Blog\Domain\User;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $blogRepository = new BlogRepository(Database::getConnection());
        $blogRepository->deleteAll();
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        self::assertNotNull($result);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);
    }

    public function testFindByIdSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertNotNull($result);
    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById(uniqid());
        self::assertNull($result);
    }

    public function testDeleteByIdSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertNotNull($result);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);
        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }
}
