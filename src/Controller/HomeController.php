<?php

namespace Blog\Controller;

use Blog\App\View;

class HomeController
{
    public function index()
    {
        View::render('Home/index', [
            "title" => "Blog PHP"
        ]);
    }
}