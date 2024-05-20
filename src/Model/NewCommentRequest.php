<?php

namespace Blog\Model;

class NewCommentRequest
{
    public ?string $id;
    public ?string $content;
    public ?string $userId;
    public ?string $blogId;
}