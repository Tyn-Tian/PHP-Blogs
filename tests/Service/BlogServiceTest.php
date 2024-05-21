<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\User;
use Blog\Exception\ValidationException;
use Blog\Model\EditBlogRequest;
use Blog\Model\NewblogRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\CommentRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class BlogServiceTest extends TestCase
{
    private BlogService $blogService;
    private UserRepository $userRepository;
    private BlogRepository $blogRepository;

    protected function setUp(): void
    {
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $commentRepository = new CommentRepository(Database::getConnection());
        $this->blogService = new BlogService($this->blogRepository);

        $commentRepository->deleteAll();
        $this->blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPassword", PASSWORD_BCRYPT);
        $this->userRepository->save($user);
    }

    public function testAddNewBlogSuccess()
    {
        $userId =  $this->userRepository->findByEmail("test@gmail.com")->id;

        $request = new NewblogRequest();
        $request->id = uniqid();
        $request->title = "testTitle";
        $request->content = "testContent";
        $request->userId = $userId;

        $response = $this->blogService->addNewBlog($request);

        self::assertEquals($request->id, $response->blog->id);
        self::assertEquals($request->title, $response->blog->title);
        self::assertEquals($request->content, $response->blog->content);
    }

    public function testAddNewBlogFailed()
    {
        $request = new NewblogRequest();
        $request->id = "";
        $request->title = "";
        $request->content = "";
        $request->userId = "";

        $this->expectException(ValidationException::class);

        $this->blogService->addNewBlog($request);
    }

    public function testAddNewBlogAlreadyExist()
    {
        $userId =  $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $request = new NewblogRequest();
        $request->id = $blog->id;
        $request->title = "testTitle";
        $request->content = "testContent";
        $request->userId = $userId;

        $this->expectException(ValidationException::class);

        $this->blogService->addNewBlog($request);
    }

    public function testDeleteBlogSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->blogService->deleteBlog($blog->id, $userId);

        $findBlog = $this->blogRepository->findById($blog->id);

        self::assertNull($findBlog);
    }

    public function testDeleteBlogNotFound()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->expectException(ValidationException::class);

        $this->blogService->deleteBlog("not found", $userId);
    }

    public function testDeleteBlogUserIdNotSame()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->expectException(ValidationException::class);
        
        $this->blogService->deleteBlog($blog->id, uniqid());
    }

    public function testEditBlogSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $request = new EditBlogRequest();
        $request->id = $blog->id;
        $request->title = "testTitleChange";
        $request->content = "testContentChange";
        $request->userId = $blog->userId;

        $response = $this->blogService->editBlog($request, $userId);

        self::assertEquals($request->title, $response->blog->title);
        self::assertEquals($request->content, $response->blog->content);
    }

    public function testEditBlogNotExist()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $request = new EditBlogRequest();
        $request->id = "not exist";
        $request->title = "testTitleChange";
        $request->content = "testContentChange";
        $request->userId = $blog->userId;

        $this->expectException(ValidationException::class);

        $this->blogService->editBlog($request, $userId);
    }

    public function testEditBlogUserIdNotSame()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $request = new EditBlogRequest();
        $request->id = $blog->id;
        $request->title = "testTitleChange";
        $request->content = "testContentChange";
        $request->userId = "not same";

        $this->expectException(ValidationException::class);

        $this->blogService->editBlog($request, $userId);
    }
}
