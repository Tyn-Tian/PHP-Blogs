<?php

use Blog\Config\Database;
use Blog\Domain\User;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = "testPass";

        $result = $this->userRepository->save($user);

        self::assertNotNull($result);
        self::assertEquals($user, $result);
    }

    public function testFindByIdSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = "testPass";

        $newUser = $this->userRepository->save($user);

        $findUser = $this->userRepository->findById($user->id);

        self::assertNotNull($findUser);
        self::assertEquals($newUser, $findUser);
    }

    public function testFindByIdNotFound()
    {
        $findUser = $this->userRepository->findById("none");
        self::assertNull($findUser);
    }

    public function testDeleteAllSuccess()
    {
        $user1 = new User();
        $user1->id = uniqid();
        $user1->email = "test1@gmail.com";
        $user1->username = "testName";
        $user1->password = "testPass";

        $user2 = new User();
        $user2->id = uniqid();
        $user2->email = "test2@gmail.com";
        $user2->username = "testName";
        $user2->password = "testPass";

        $this->userRepository->save($user1);
        $this->userRepository->save($user2);

        $this->userRepository->deleteAll();

        $findUser1 = $this->userRepository->findById($user1->id);
        $findUser2 = $this->userRepository->findById($user2->id);

        self::assertNull($findUser1);
        self::assertNull($findUser2);
    }
}