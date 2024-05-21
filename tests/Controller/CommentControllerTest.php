<?php

namespace Blog\Controller;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\Comment;
use Blog\Domain\Session;
use Blog\Domain\User;
use Blog\Repository\BlogRepository;
use Blog\Repository\CommentRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\SessionService;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../helper/Helper.php";

class CommentControllerTest extends TestCase
{
    private CommentController $commentController;
    private BlogRepository $blogRepository;
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->commentController = new CommentController();

        $this->commentRepository->deleteAll();
        $this->blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "userId";
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

    public function testPostNewComment()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $_POST['content'] = "testContent";

        $this->commentController->postNewComment($blog->id);

        $this->expectOutputRegex("[Location: /blog-$blog->id/detail]");
    }

    public function testPostNewCommentValidationError()
    {
        $userId = $this->userRepository->findByEmail("test@gmail.com")->id;

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $userId;
        $this->blogRepository->save($blog);

        $_POST['content'] = "";

        $this->commentController->postNewComment($blog->id);

        $this->expectOutputRegex("[Comment cannot be blank]");
    }

    public function testPostDeleteCommentSuccess()
    {
        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = "userId";
        $this->blogRepository->save($blog);

        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testContent";
        $comment->userId = "userId";
        $comment->blogId = $blog->id;
        $this->commentRepository->save($comment);

        $this->commentController->postDeleteComment($comment->id);
        $this->expectOutputRegex("[Location: /blog-$blog->id/detail]");
    }

    public function testPostDeleteCommentValidationError()
    {
        $otherUser = new User();
        $otherUser->id = uniqid();
        $otherUser->email = "test1@gmail.com";
        $otherUser->username = "test1Name";
        $otherUser->password = password_hash("testPass", PASSWORD_BCRYPT);
        $this->userRepository->save($otherUser);

        $blog = new Blog();
        $blog->id = uniqid();
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = "userId";
        $this->blogRepository->save($blog);

        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testContent";
        $comment->userId = $otherUser->id;
        $comment->blogId = $blog->id;
        $this->commentRepository->save($comment);

        $this->commentController->postDeleteComment($comment->id);
        $this->expectOutputRegex("[Location: /blog-$blog->id/detail]");
    }
}