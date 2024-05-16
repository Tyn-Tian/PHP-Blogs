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

    protected function setUp(): void
    {
        $this->blogController = new BlogController();
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $blogRepository = new BlogRepository(Database::getConnection());

        $blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $userRepository->deleteAll();

        $user = new User();
        $user->id = uniqid();
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $userRepository->save($user);

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
}