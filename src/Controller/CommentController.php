<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
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

    public function __construct()
    {
        $this->commentRepository = new CommentRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->commentService = new CommentService($this->commentRepository);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
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