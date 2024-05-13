<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
use Blog\Model\UserLoginRequest;
use Blog\Model\UserRegisterRequest;
use Blog\Repository\UserRepository;
use Blog\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
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
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('Users/login', [
                "title" => "User Login",
                "error" => $exception->getMessage()
            ]);
        }
    }
}