<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\Comment;
use Blog\Exception\ValidationException;
use Blog\Model\NewBlogResponse;
use Blog\Model\NewCommentRequest;
use Blog\Model\NewCommentResponse;
use Blog\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        private CommentRepository $commentRepository
    ) {
    }

    public function addNewComment(NewCommentRequest $request): NewCommentResponse
    {
        $this->validateNewCommentRequest($request);

        try {
            Database::beginTransaction();

            $comment = new Comment();
            $comment->id = $request->id;
            $comment->content = $request->content;
            $comment->userId = $request->userId;
            $comment->blogId = $request->blogId;
            $this->commentRepository->save($comment);

            $response = new NewCommentResponse();
            $response->comment = $this->commentRepository->findById($comment->id);

            Database::commit();
            return $response;
        } catch (ValidationException $exception) {
            Database::rollBack();
            throw $exception;
        }
    }

    private function validateNewCommentRequest(NewCommentRequest $request) 
    {
        if (
            $request->content == null || trim($request->content) == ""
        ) {
            throw new ValidationException("Comment cannot be blank");
        }
    }
}
