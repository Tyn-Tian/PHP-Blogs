<?php

use Blog\App\Router;
use Blog\Config\Database;
use Blog\Controller\HomeController;
use Blog\Controller\UserController;
use Blog\Controller\BlogController;
use Blog\Controller\CommentController;
use Blog\Middleware\MustLoginMiddleware;
use Blog\Middleware\MustNotLoginMiddleware;

require_once __DIR__ . "/../vendor/autoload.php";

Database::getConnection('prod');

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/new-blog', BlogController::class, 'newBlog', [MustLoginMiddleware::class]);
Router::add('POST', '/new-blog', BlogController::class, 'postNewBlog', [MustLoginMiddleware::class]);
Router::add('GET', '/{username}', UserController::class, 'userProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/blog-{blogId}/detail', BlogController::class, 'blogDetail', [MustLoginMiddleware::class]);
Router::add('GET', '/delete/{blogId}', BlogController::class, 'postDeleteBlog', [MustLoginMiddleware::class]);
Router::add('GET', '/blog-{blogId}/edit', BlogController::class, 'editBlog', [MustLoginMiddleware::class]);
Router::add('POST', '/blog-{blogId}/edit', BlogController::class, 'postEditBlog', [MustLoginMiddleware::class]);
Router::add('POST', '/blog-{blogId}/new-comment', CommentController::class, 'postNewComment', [MustLoginMiddleware::class]);
Router::add('GET', '/comment/delete/{commentId}', CommentController::class, 'postDeleteComment', [MustLoginMiddleware::class]);

Router::run();