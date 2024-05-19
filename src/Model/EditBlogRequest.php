<?php

namespace Blog\Model;

class EditBlogRequest
{
    public ?string $id;
    public ?string $title;
    public ?string $content;
    public ?string $userId;
}
