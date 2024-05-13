<?php

namespace Blog\Controller;

use Blog\Config\Database;
use Blog\Domain\User;
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

    public function testPostRegisterValidationError()
    {
        $_POST['email'] = "";
        $_POST['username'] = "";
        $_POST['password'] = "";

        $this->userController->postRegister();

        $this->expectOutputRegex("[Email, username, password cannot be blank]");
    }

    public function testPostRegisterEmailRegistered()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = "testPass";

        $this->userRepository->save($user);

        $_POST['email'] = "test@gmail.com";
        $_POST['username'] = "testName2";
        $_POST['password'] = "testPass2";

        $this->userController->postRegister();

        $this->expectOutputRegex("[Email is registered]");
    }

    public function testLogin()
    {
        $this->userController->login();

        $this->expectOutputRegex("[Welcome Back.]");
        $this->expectOutputRegex("[Email]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[No account?]");
    }

    public function testPostLoginSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $_POST['email'] = "test@gmail.com";
        $_POST['password'] = "testPass";
        $this->userController->postLogin();

        $this->expectOutputRegex("[Location: /]");
    }

    public function testPostLoginValidationError()
    {
        $_POST['email'] = "";
        $_POST['password'] = "";
        $this->userController->postLogin();

        $this->expectOutputRegex("[Email and Password cannot be blank]");
    }

    public function testPostLoginUserNotFound()
    {
        $_POST['email'] = "test@gmail.com";
        $_POST['password'] = "testPass";
        $this->userController->postLogin();

        $this->expectOutputRegex("[Email or password is wrong]");
    }

    public function testPostLoginWrongPassword()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $_POST['email'] = "test@gmail.com";
        $_POST['password'] = "salah";
        $this->userController->postLogin();

        $this->expectOutputRegex("[Email or password is wrong]");
    }
}
