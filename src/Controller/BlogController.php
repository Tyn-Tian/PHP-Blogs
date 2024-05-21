<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
use Blog\Model\EditBlogRequest;
use Blog\Model\NewblogRequest;
use Blog\Model\NewCommentRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\CommentRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\BlogService;
use Blog\Service\CommentService;
use Blog\Service\SessionService;

class BlogController
{
    private BlogService $blogService;
    private SessionService $sessionService;
    private BlogRepository $blogRepository;
    private UserRepository $userRepository;
    private CommentService $commentService;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $commentRepository = new CommentRepository(Database::getConnection());
        $this->blogService = new BlogService($this->blogRepository);
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
        $this->commentService = new CommentService($commentRepository);
    }

    public function newBlog()
    {
        $user = $this->sessionService->current();

        View::render('Blog/new-blog', [
            "title" => "New Blog - PHP Blog",
            "username" => $user->username
        ]);
    }

    public function postNewBlog()
    {
        $user = $this->sessionService->current();

        $request = new NewblogRequest();
        $request->id = uniqid();
        $request->title = $_POST['title'];
        $request->content = $_POST['content'];
        $request->userId = $user->id;

        try {
            $this->blogService->addNewBlog($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('Blog/new-blog', [
                "title" => "New Blog - PHP Blog",
                "username" => $user->username,
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function blogDetail($blogId)
    {
        $currentUser = $this->sessionService->current();
        $blog = $this->blogRepository->findById($blogId);
        $username = $this->userRepository->findById($blog->userId)->username;

        View::render('Blog/detail-blog', [
            "title" => $blog->title,
            "blog" => $blog,
            "currentUsername" => $currentUser->username,
            "username" => $username
        ]);
    }

    public function postDeleteBlog($blogId)
    {
        $user = $this->sessionService->current();

        try {
            $this->blogService->deleteBlog($blogId, $user->id);
            View::redirect("/");
        } catch (ValidationException $exception) {
            View::redirect("/blog-$blogId/detail");
        }
    }

    public function editBlog($blogId)
    {
        $blog = $this->blogRepository->findById($blogId);
        $user = $this->sessionService->current();

        if ($blog->userId != $user->id) {
            View::redirect("/blog-$blogId/detail");
        } else {
            View::render('Blog/new-blog', [
                "title" => "New Blog - PHP Blog",
                "username" => $user->username,
                "blog" => $blog
            ]);
        }
    }

    public function postEditBlog($blogId)
    {
        $user = $this->sessionService->current();
        $blog = $this->blogRepository->findById($blogId);

        $request = new EditBlogRequest();
        $request->id = $blogId;
        $request->title = $_POST['title'];
        $request->content = $_POST['content'];
        $request->userId = $blog->userId;

        try {
            $this->blogService->editBlog($request, $user->id);
            View::redirect("/");
        } catch (ValidationException $exception) {
            View::render('Blog/new-blog', [
                "title" => "New Blog - PHP Blog",
                "username" => $user->username,
                "blog" => $blog,
                "error" => $exception->getMessage()
            ]);
        }
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

            View::render('Blog/detail-blog', [
                "title" => $blog->title,
                "blog" => $blog,
                "currentUsername" => $user->username,
                "username" => $username,
                "error" => $exception->getMessage()
            ]);
        }
    }
}
