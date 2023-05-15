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

    public function testConvertToStudlyCase()
    {
        $router = new Router();
        $kebabCase = 'this-should-be-studly-case';

        $studlyCase = $router->converToStudlyCaps($kebabCase);

        $this->assertSame('ThisShouldBeStudlyCase', $studlyCase);
    }

    /* public function testRemoveQueryStringVariables()
    {
        $router = new Router();
        $url = '/some/path/somewhere?something=where&other=else';

        $cleanedUp = $router->removeQueryStringVariables($url);

        $this->assertSame('/some/path/somewhere', $cleanedUp);
        //removeQueryStringVariables()
    } */
}