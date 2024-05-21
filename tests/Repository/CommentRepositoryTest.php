<?php

namespace Blog\Repository;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Domain\Comment;
use Blog\Domain\User;
use PHPUnit\Framework\TestCase;

class CommentRepositoryTest extends TestCase
{
    private CommentRepository $commentRepository;

    protected function setUp(): void
    {
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());

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

    public function testSaveSuccess()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $findComment = $this->commentRepository->findById($comment->id);

        self::assertEquals($comment->id, $findComment->id);
        self::assertEquals($comment->content, $findComment->content);
        self::assertEquals($comment->userId, $findComment->userId);
        self::assertEquals($comment->blogId, $findComment->blogId);
    }

    public function testFindByIdSuccess()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $findComment = $this->commentRepository->findById($comment->id);

        self::assertNotNull($findComment);
    }

    public function testFindByIdNotFound()
    {
        $findComment = $this->commentRepository->findById("not found");
        self::assertNull($findComment);
    }

    public function testDeleteAllSuccess()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $findComment = $this->commentRepository->findById($comment->id);
        self::assertNotNull($findComment);

        $this->commentRepository->deleteAll();

        $findComment = $this->commentRepository->findById($comment->id);
        self::assertNull($findComment);
    }

    public function testDeleteById()
    {
        $comment = new Comment();
        $comment->id = uniqid();
        $comment->content = "testCommentContent";
        $comment->blogId = "blogId";
        $comment->userId = "userId";
        $this->commentRepository->save($comment);

        $this->commentRepository->deleteById($comment->id);

        $findComment = $this->commentRepository->findById($comment->id);    
        self::assertNull($findComment);
    }
}