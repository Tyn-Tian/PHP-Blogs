<?php

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\User;
use Blog\Repository\BlogRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private BlogRepository $blogRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $sessionRepository->deleteAll();
        $this->blogRepository->deleteAll();
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
        $user1->username = "testName1";
        $user1->password = "testPass";

        $user2 = new User();
        $user2->id = uniqid();
        $user2->email = "test2@gmail.com";
        $user2->username = "testName2";
        $user2->password = "testPass";

        $this->userRepository->save($user1);
        $this->userRepository->save($user2);

        $this->userRepository->deleteAll();

        $findUser1 = $this->userRepository->findById($user1->id);
        $findUser2 = $this->userRepository->findById($user2->id);

        self::assertNull($findUser1);
        self::assertNull($findUser2);
    }

    public function testFindByEmailSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = "testPass";

        $newUser = $this->userRepository->save($user);

        $findUser = $this->userRepository->findByEmail($user->email);

        self::assertNotNull($findUser);
        self::assertEquals($newUser, $findUser);
    }

    public function testFindByEmailNotFound()
    {
        $findUser = $this->userRepository->findByEmail("empty");
        self::assertNull($findUser);
    }

    public function testFindByUsernameSuccess()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = "testPass";

        $newUser = $this->userRepository->save($user);

        $findUser = $this->userRepository->findByUsername($user->username);

        self::assertNotNull($findUser);
        self::assertEquals($newUser, $findUser);
    }

    public function testFindByUsernameNotFound()
    {
        $findUser = $this->userRepository->findByEmail("empty");
        self::assertNull($findUser);
    }

    public function testFindAllBlog()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $blog1 = new Blog();
        $blog1->id = uniqid();
        $blog1->title = "testTitle";
        $blog1->content = "testContent";
        $blog1->userId = $user->id;
        $this->blogRepository->save($blog1);

        $blog2 = new Blog();
        $blog2->id = uniqid();
        $blog2->title = "testTitle";
        $blog2->content = "testContent";
        $blog2->userId = $user->id;
        $this->blogRepository->save($blog2);

        $result = $this->userRepository->findAllBlog($user->id);

        self::assertIsArray($result);
        self::assertCount(2, $result);
    }


    public function testFindAllBlogEmpty()
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $result = $this->userRepository->findAllBlog($user->id);

        self::assertNull($result);
    }
}