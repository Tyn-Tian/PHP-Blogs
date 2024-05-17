<?php

use Blog\App\Router;
use Blog\Config\Database;
use Blog\Controller\HomeController;
use Blog\Controller\UserController;
use Blog\Controller\BlogController;
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

Router::run();