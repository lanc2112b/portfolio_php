<?php

use PHPUnit\Framework\TestCase;
use \Core\Router;

class CoreRouterTest extends TestCase
{
    public function testConvertToCamelCase()
    {
        $router = new Router();
        $studlyCase = 'ThisIsCamelCase';

        $camelCase = $router->convertToCamelCase($studlyCase);

        $this->assertSame('thisIsCamelCase', $camelCase);
    }
}