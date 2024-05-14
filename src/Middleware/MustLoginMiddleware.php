<?php

namespace Blog\Middleware;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function before(): void
    {
        $user = $this->sessionService->current();

        if ($user == null) {
            View::redirect('/users/login');
        }
    }
}