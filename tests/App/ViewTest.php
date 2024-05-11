<?php

namespace Blog\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testView()
    {
        View::render('Home/index', [
            "title" => "PHP Blog"
        ]);

        $this->expectOutputRegex("[PHP Blog]");
        $this->expectOutputRegex("[html]");
        $this->expectOutputRegex("[body]");
        $this->expectOutputRegex("[Get reading]");
    }
}