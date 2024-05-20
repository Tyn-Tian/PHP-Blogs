<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\User;
use Blog\Exception\ValidationException;
use Blog\Model\UserLoginRequest;
use Blog\Model\UserRegisterRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private UserService $userService;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $blogRepository = new BlogRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);

        $blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = uniqid();
        $request->email = "test@gmail.com";
        $request->username = "testName";
        $request->password = "testPass";

        $response = $this->userService->register($request);
        
        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->email, $response->user->email);
        self::assertEquals($request->username, $response->user->username);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->email = "";
        $request->username = "";
        $request->password = "";

        $this->userService->register($request);
    }

    public function testRegisterEmailDuplicate()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testNameUser";
        $user->password = "testPassUser";
        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = uniqid();
        $request->email = "test@gmail.com";
        $request->username = "testNameRequest";
        $request->password = "testPassRequest";

        $this->userService->register($request);
    }

    public function testRegisterUsernameDuplicate()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testNameUser";
        $user->password = "testPassUser";
        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = uniqid();
        $request->email = "test@gmail.com";
        $request->username = "testNameUser";
        $request->password = "testPassRequest";

        $this->userService->register($request);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);  
        $this->userRepository->save($user);

        $request = new UserLoginRequest();
        $request->email = "test@gmail.com";
        $request->password = "testPass";

        $response = $this->userService->login($request);

        self::assertNotNull($response);
        self::assertEquals($user->id, $response->user->id);
        self::assertEquals($user->email, $response->user->email);
        self::assertEquals($user->username, $response->user->username);
    }

    public function testLoginValidationError()
    {
        $request = new UserLoginRequest();
        $request->email = "";
        $request->password = "";

        $this->expectException(ValidationException::class);

        $this->userService->login($request);
    }

    public function testLoginUserNotFound()
    {
        $request = new UserLoginRequest();
        $request->email = "test@gmail.com";
        $request->password = "testPass";

        $this->expectException(ValidationException::class);

        $this->userService->login($request);
    }

    public function testLoginWrongPassword()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);  
        $this->userRepository->save($user);

        $request = new UserLoginRequest();
        $request->email = "test@gmail.com";
        $request->password = "salah";

        $this->expectException(ValidationException::class);

        $this->userService->login($request);
    }
}