<?php

namespace Blog\Repository;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\User;
use PHPUnit\Framework\TestCase;

class BlogRepositoryTest extends TestCase
{
    private BlogRepository $blogRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $sessionRepository->deleteAll();
        $this->blogRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $userId = $this->userRepository->findByEmail('test@gmail.com')->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testcontent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $result = $this->blogRepository->findById($blog->id);

        self::assertNotNull($result);
        self::assertEquals($blog->id, $result->id);
        self::assertEquals($blog->userId, $result->userId);
    }

    public function testFindByIdSuccess()
    {
        $userId = $this->userRepository->findByEmail('test@gmail.com')->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $findBlog = $this->blogRepository->findById($blog->id);

        self::assertNotNull($findBlog);
    }

    public function testFindByIdNotFound()
    {
        $findBlog = $this->blogRepository->findById('blog');
        self::assertNull($findBlog);
    }

    public function testDeleteAllSuccess()
    {
        $userId = $this->userRepository->findByEmail('test@gmail.com')->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $findBlog = $this->blogRepository->findById($blog->id);
        self::assertNotNull($findBlog);

        $this->blogRepository->deleteAll();
        $findBlog = $this->blogRepository->findById($blog->id);
        self::assertNull($findBlog);
    }
}