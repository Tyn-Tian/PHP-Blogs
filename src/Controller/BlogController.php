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

    public function __construct()
    {
        $blogRepository = new BlogRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->blogService = new BlogService($blogRepository);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function newBlog()
    {
        View::render('Blog/new-blog', [
            "title" => "New Blog - PHP Blog"
        ]);
    }

    public function postNewBlog()
    {
        $userId = $this->sessionService->current()->id;

        $request = new NewblogRequest();
        $request->id = uniqid();
        $request->title = $_POST['title'];
        $request->content = $_POST['content'];
        $request->userId = $userId;

        try {
            $this->blogService->addNewBlog($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('Blog/new-blog', [
                "title" => "New Blog - PHP Blog",
                "error" => $exception->getMessage()
            ]);
        }
    }
}