<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\Comment;
use Blog\Domain\User;
use Blog\Exception\ValidationException;
use Blog\Model\NewCommentRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\CommentRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class CommentServiceTest extends TestCase
{
    private CommentRepository $commentRepository;
    private CommentService $commentService;

    protected function setUp(): void
    {
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->commentService = new CommentService($this->commentRepository);

        $this->commentRepository->deleteAll();
        $blogRepository->deleteAll();
        $sessionRepository->deleteAll();
        $userRepository->deleteAll();

        $user = new User();
        $user->id = "userId";
        $user->email = "test@gmail.com";
        $user->username = "testName";
        $user->password = password_hash("testPass", PASSWORD_BCRYPT);
        $userRepository->save($user);

        $blog = new Blog();
        $blog->id = "blogId";
        $blog->title = "testTitle";
        $blog->content = "testContent";
        $blog->userId = $user->id;
        $blogRepository->save($blog);
    }

    public function testAddNewCommentSuccess()
    {
        $request = new NewCommentRequest();
        $request->id = uniqid();
        $request->content = "testContent";
        $request->userId = "userId";
        $request->blogId = "blogId";

        $response = $this->commentService->addNewComment($request);

        self::assertEquals($request->id, $response->comment->id);
        self::assertEquals($request->content, $response->comment->content);
        self::assertEquals($request->userId, $response->comment->userId);
        self::assertEquals($request->blogId, $response->comment->blogId);
    }

    public function testAddNewCommentValidationError()
    {
        $request = new NewCommentRequest();
        $request->id = uniqid();
        $request->content = "";
        $request->userId = "userId";
        $request->blogId = "blogId";

        $this->expectException(ValidationException::class);
        $this->commentService->addNewComment($request);
    }

    public function testDeleteCommentSuccess()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $this->commentService->deleteComment($comment->id, "userId");

        $findComment = $this->commentRepository->findById($comment->id);
        self::assertNull($findComment);
    }

    public function testDeleteCommentNotExist()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $this->expectException(ValidationException::class);

        $this->commentService->deleteComment("no found", "userId");
    }

    public function testDeleteCommentUserIdNotSame()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $this->expectException(ValidationException::class);

        $this->commentService->deleteComment($comment->id, "userIdDiff");
    }
}