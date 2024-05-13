<?php

namespace Blog\App {
    function header(string $value)
    {
        echo $value;
    }
}

namespace Blog\Service {
    function setcookie(string $name, string $value) 
    {
        echo "$name: $value";
    }
}