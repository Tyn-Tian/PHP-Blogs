<?php

namespace Blog\Repository;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\Comment;
use Blog\Domain\User;
use PHPUnit\Framework\TestCase;

class BlogRepositoryTest extends TestCase
{
    private BlogRepository $blogRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;

    protected function setUp(): void
    {
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $this->commentRepository->deleteAll();
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

    public function testFindAllBlogExceptCurrentUser()
    {
        $user1 = new User();
        $user1->id = uniqid();
        $user1->email = "test1@gmail.com";
        $user1->username = "test1Name";
        $user1->password = password_hash("test1Pass", PASSWORD_BCRYPT);
        $this->userRepository->save($user1);

        $blog1 = new Blog();
        $blog1->id = uniqid();
        $blog1->title = "test1Title";
        $blog1->content = "test1Content";
        $blog1->userId = $user1->id;
        $this->blogRepository->save($blog1);

        $user2 = new User();
        $user2->id = uniqid();
        $user2->email = "test2@gmail.com";
        $user2->username = "test2Name";
        $user2->password = password_hash("test1Pass", PASSWORD_BCRYPT);
        $this->userRepository->save($user2);

        $blog2 = new Blog();
        $blog2->id = uniqid();
        $blog2->title = "test2Title";
        $blog2->content = "test2Content";
        $blog2->userId = $user2->id;
        $this->blogRepository->save($blog2);

        $result = $this->blogRepository->findAllBlogExceptCurrentUser($user1->id);
        self::assertIsArray($result);
        self::assertCount(1, $result);
    }

    public function testFindAllBlogExceptCurrentUserEmpty() 
    {
        $user1 = new User();
        $user1->id = uniqid();
        $user1->email = "test1@gmail.com";
        $user1->username = "test1Name";
        $user1->password = password_hash("test1Pass", PASSWORD_BCRYPT);
        $this->userRepository->save($user1);

        $blog1 = new Blog();
        $blog1->id = uniqid();
        $blog1->title = "test1Title";
        $blog1->content = "test1Content";
        $blog1->userId = $user1->id;
        $this->blogRepository->save($blog1);

        $result = $this->blogRepository->findAllBlogExceptCurrentUser($user1->id);
        self::assertNull($result);
    }

    public function testDeleteById()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "test1Title";
        $blog->content = "test1Content";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->blogRepository->deleteById($blog->id);

        $findBlog = $this->blogRepository->findById($blog->id);

        self::assertNull($findBlog);
    }

    public function testUpdate()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $blog->title = "testTitleChange";
        $blog->content = "testContentChange";
        $response = $this->blogRepository->update($blog);

        self::assertEquals($blog->title, $response->title);
        self::assertEquals($blog->content, $response->content);
    }

    public function testFindAllCommentInBlog()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testContent";
        $comment->userId = $userId;
        $comment->blogId = $blog->id;
        $this->commentRepository->save($comment);

        $result = $this->blogRepository->findAllCommentInBlog($blog->id);
        self::assertIsArray($result);
        self::assertCount(1, $result);
    }

    public function testFindAllCommentInBlogEmpty()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $result = $this->blogRepository->findAllCommentInBlog($blog->id);
        self::assertNull($result);
    }
}