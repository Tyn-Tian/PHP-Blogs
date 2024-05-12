<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\User;
use Blog\Exception\ValidationException;
use Blog\Model\UserRegisterRequest;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private UserService $userService;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);

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
}