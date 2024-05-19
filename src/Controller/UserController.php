<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
use Blog\Model\UserLoginRequest;
use Blog\Model\UserRegisterRequest;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;
use Blog\Service\UserService;

class UserController
{
    private UserRepository $userRepository;
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
    }

    public function register()
    {
        View::render('Users/register', [
            "title" => "User Register"
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = uniqid();
        $request->email = $_POST['email'];
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch(ValidationException $validation) {
            View::render('Users/register', [
                "title" => "User Register",
                "error" => $validation->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::render('Users/login', [
            "title" => "User Login"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->email = $_POST['email'];
        $request->password = $_POST['password'];

        try {
            $this->userService->login($request);
            $userId = $this->userRepository->findByEmail($request->email)->id;
            $this->sessionService->create($userId);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('Users/login', [
                "title" => "User Login",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destory();
        View::redirect('/');
    }

    public function userProfile($username)
    {
        $user = $this->sessionService->current();
        $currentProfile = ($user->username == $username);

        if ($currentProfile) {
            $blogs = $this->userRepository->findAllBlog($user->id);
            
        } else {
            $userId = $this->userRepository->findByUsername($username)->id;
            $blogs = $this->userRepository->findAllBlog($userId);
        }

        View::render('Users/profile', [
            "title" => "$user->username - PHP Blog",
            "username" => $username,
            "currentUsername" => $user->username,
            "blogs" => $blogs,
            "currentProfile" => $currentProfile
        ]);
    }
}