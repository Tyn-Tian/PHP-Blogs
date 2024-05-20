<?php

namespace Blog\Controller;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\Session;
use Blog\Domain\User;
use Blog\Repository\BlogRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../helper/Helper.php";

class BlogControllerTest extends TestCase
{
    private BlogController $blogController;
    private UserRepository $userRepository;
    private BlogRepository $blogRepository;

    protected function setUp(): void
    {
        $this->blogController = new BlogController();
        $this->userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->blogRepository = new BlogRepository(Database::getConnection());

        $this->blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        putenv("mode=test");
    }

    public function testNewBlog()
    {
        $this->blogController->newBlog();

        $this->expectOutputRegex("[textarea]");
        $this->expectOutputRegex("[Publish]");
        $this->expectOutputRegex("[New Blog - PHP Blog]");
    }

    public function testPostNewBlogSuccess()
    {
        $_POST['title'] = "blogTitle";
        $_POST['content'] = "blogContent";

        $this->blogController->postNewBlog();

        $this->expectOutputRegex("[Location: /]");
    }

    public function testPostNewBlogValidationError()
    {
        $_POST['title'] = "";
        $_POST['content'] = "";

        $this->blogController->postNewBlog();

        $this->expectOutputRegex("[Title and Content cannot be blank]");
    }

    public function testBlogDetail()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->blogController->blogDetail($blog->id);

        $this->expectOutputRegex("[testTitle]");
        $this->expectOutputRegex("[testContent]");
    }

    public function testPostDeleteBlog()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->blogController->postDeleteBlog($blog->id);

        $this->expectOutputRegex("[Location: /]");
    }

    public function testPostDeleteBlogValidationError()
    {
        $otherUser = new User();
        $otherUser->id = uniqid();
        $otherUser->email = "test2@gmail.com";
        $otherUser->username = "test2Name";
        $otherUser->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($otherUser);

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $otherUser->id;
        $this->blogRepository->save($blog);

        $this->blogController->postDeleteBlog($blog->id);

        $this->expectOutputRegex("[Location: /blog-$blog->id/detail]");
    }

    public function testEditBlog()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $this->blogController->editBlog($blog->id);

        $this->expectOutputRegex("[textarea]");
        $this->expectOutputRegex("[Publish]");
        $this->expectOutputRegex("[New Blog - PHP Blog]");
    }

    public function testPostEditBlogSuccess()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $_POST['title'] = "testTitleChange";
        $_POST['content'] = "testContentChange";

        $this->blogController->postEditBlog($blog->id);

        $this->expectOutputRegex("[Location: /]");
    }

    public function testPostEditBlogValidationError()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $_POST['title'] = "";
        $_POST['content'] = "";

        $this->blogController->postEditBlog($blog->id);

        $this->expectOutputRegex("[Title and Content cannot be blank]");
    }
}