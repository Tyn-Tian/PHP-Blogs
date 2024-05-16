<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function index()
    {
        $user = $this->sessionService->current();

        if ($user == null) {
            View::render('Home/index', [
                "title" => "Blog PHP"
            ]);
        } else {
            View::render('Home/dashboard', [
                "title" => "Blog PHP",
                "username" => $user->username
            ]);
        }
    }
}