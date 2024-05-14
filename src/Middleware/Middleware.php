<?php

namespace Blog\Middleware;

interface Middleware 
{
    function before(): void;
}