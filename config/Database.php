<?php

function getDatabaseConfig()
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=php_blog_test",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=php_blog",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}
