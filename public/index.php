<?php

use Blog\App\Router;
use Blog\Controller\HomeController;

require_once __DIR__ . "/../vendor/autoload.php";

Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();