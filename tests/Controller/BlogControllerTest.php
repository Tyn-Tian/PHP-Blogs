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
}