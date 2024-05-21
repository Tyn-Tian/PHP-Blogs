<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
use Blog\Model\NewCommentRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\CommentRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\CommentService;
use Blog\Service\SessionService;

class CommentController
{
    private CommentRepository $commentRepository;
    private CommentService $commentService;
    private SessionService $sessionService;
    private UserRepository $userRepository;
    private BlogRepository $blogRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $this->commentService = new CommentService($this->commentRepository);
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
    }

    public function postNewComment($blogId)
    {
        $user = $this->sessionService->current();

        $request = new NewCommentRequest();
        $request->id = uniqid();
        $request->content = $_POST['content'];
        $request->userId = $user->id;
        $request->blogId = $blogId;

        try {
            $this->commentService->addNewComment($request);
            View::redirect("/blog-$blogId/detail");
        } catch (ValidationException $exception) {
            $blog = $this->blogRepository->findById($blogId);
            $username = $this->userRepository->findById($blog->userId)->username;
            $comments = $this->blogRepository->findAllCommentInBlog($blogId);

            View::render('Blog/detail-blog', [
                "title" => $blog->title,
                "blog" => $blog,
                "currentUsername" => $user->username,
                "username" => $username,
                "comments" => $comments,
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function postDeleteComment(string $commentId)
    {
        $user = $this->sessionService->current();
        $blogId = $this->commentRepository->findById($commentId)->blogId;

        try {
            $this->commentService->deleteComment($commentId, $user->id);
            View::redirect("/blog-$blogId/detail");
        } catch (ValidationException $exception) {
            View::redirect("/blog-$blogId/detail");
        }
    }
}