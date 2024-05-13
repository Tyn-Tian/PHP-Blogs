<?php

use Blog\App\Router;
use Blog\Config\Database;
use Blog\Controller\HomeController;
use Blog\Controller\UserController;

require_once __DIR__ . "/../vendor/autoload.php";

Database::getConnection('prod');

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/users/register', UserController::class, 'register', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);

Router::run();