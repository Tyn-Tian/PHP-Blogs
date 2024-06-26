<?php

namespace Blog\Config;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testConnection()
    {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

    public function testGetConnectionSingleton()
    {
        $connection1 = Database::getConnection();
        $connection2 = Database::getConnection();

        self::assertEquals($connection1, $connection2);
    }
}