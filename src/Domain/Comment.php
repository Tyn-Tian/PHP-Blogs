<?php

namespace Blog\Domain;

class Comment
{
    public string $id;
    public string $content;
    public string $createdAt;
    public string $userId;
    public string $blogId;
}