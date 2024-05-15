<?php 

namespace Blog\Model;

class NewblogRequest 
{
    public ?string $id;
    public ?string $title;
    public ?string $content;
    public ?string $userId;
}