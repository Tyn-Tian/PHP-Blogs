<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\Blog;
use Blog\Exception\ValidationException;
use Blog\Model\NewblogRequest;
use Blog\Model\NewBlogResponse;
use Blog\Repository\BlogRepository;

class BlogService
{
    public function __construct(
        private BlogRepository $blogRepository
    ) {
    }

    public function addNewBlog(NewblogRequest $request): NewBlogResponse
    {
        $this->validateAddNewBlog($request);

        try {
            Database::beginTransaction();

            $blog = $this->blogRepository->findById($request->id);

            if ($blog != null) {
                throw new ValidationException("Blog is already Exist");
            }

            $blog = New Blog();
            $blog->id = $request->id;
            $blog->title = $request->title;
            $blog->content = $request->content;
            $blog->userId = $request->userId;
            $this->blogRepository->save($blog);

            $response = new NewBlogResponse();
            $response->blog = $this->blogRepository->findById($blog->id);

            Database::commit();
            return $response;
        } catch (ValidationException $exception) {
            Database::rollBack();
            throw $exception;
        }
    }

    private function validateAddNewBlog(NewblogRequest $request) 
    {
        if (
            $request->title == null || $request->content == null ||
            trim($request->title) == "" || trim($request->content) == ""
        ) {
            throw new ValidationException("Title and Content cannot be blank");
        }
    }

    public function deleteBlog(string $blogId, string $userId)
    {
        try {
            Database::beginTransaction();
            
            $blog = $this->blogRepository->findById($blogId);

            if ($blog == null) {
                throw new ValidationException("Blog not found");
            }

            if ($blog->userId != $userId) {
                throw new ValidationException("This blog is not yours");
            }

            $this->blogRepository->deleteById($blogId);
            Database::commit();
        } catch (ValidationException $exception) {
            Database::rollBack();
            throw $exception;
        }
    }
}
