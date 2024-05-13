<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\Session;
use Blog\Domain\User;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../helper/Helper.php";

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPassword", PASSWORD_BCRYPT);
        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $session = $this->sessionService->create($userId);

        $this->expectOutputRegex("[X-TYN-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($userId, $result->userId);
    }

    public function testDestory()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destory();

        $this->expectOutputRegex("[X-TYN-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }
}