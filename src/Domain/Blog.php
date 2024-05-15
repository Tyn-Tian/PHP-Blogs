<?php

namespace Blog\Domain;

class Blog 
{
    public string $id;
    public string $title;
    public string $content;
    public ?string $createdAt;
    public string $userId;
}