<?php

namespace Blog\Middleware;

use Blog\Config\Database;
use Blog\Domain\Session;
use Blog\Domain\User;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../helper/Helper.php";

class MustNotLoginMiddlewareTest extends TestCase
{
    private MustNotLoginMiddleware $middleware;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->middleware = new MustNotLoginMiddleware();
        putenv("mode=test");

        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testBeforeGuest()
    {
        $this->middleware->before();
        $this->expectOutputString("");
    }

    public function testBeforeLoginuser()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);$user = new User();

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->middleware->before();
        $this->expectOutputRegex("[Location: /]");
    }
}
