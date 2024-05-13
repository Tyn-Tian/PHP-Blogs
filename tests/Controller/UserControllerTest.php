<?php

namespace Blog\Controller;

use Blog\Config\Database;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../helper/Helper.php";

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userController = new UserController();
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->userRepository->deleteAll();
        putenv("mode=test");
    }

    public function testRegister()
    {
        $this->userController->register();

        $this->expectOutputRegex("[Join PHP Blog.]");
        $this->expectOutputRegex("[Email]");
        $this->expectOutputRegex("[Username]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Already have an account?]");
    }

    public function testPostRegister()
    {
        $_POST['email'] = "test@gmail.com";
        $_POST['username'] = "testName";
        $_POST['password'] = "testPass";

        $this->userController->postRegister();

        $this->expectOutputRegex("[Location: /users/login]");
    }
}