<?php

namespace Blog\Controller;

use Blog\App\View;
use Blog\Config\Database;
use Blog\Exception\ValidationException;
use Blog\Model\NewblogRequest;
use Blog\Repository\BlogRepository;
use Blog\Repository\SessionRepository;
use Blog\Repository\UserRepository;
use Blog\Service\BlogService;
use Blog\Service\SessionService;

class BlogController
{
    private BlogService $blogService;
    private SessionService $sessionService;
    private BlogRepository $blogRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->blogService = new BlogService($this->blogRepository);
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
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
            $blogs = $this->blogRepository->findAllBlogExceptCurrentUser($user->id);

            View::render('Home/dashboard', [
                "title" => "Blog PHP",
                "username" => $user->username,
                "blogs" => $blogs,
                "error" => $exception->getMessage()
            ]);
        }
    }
}
