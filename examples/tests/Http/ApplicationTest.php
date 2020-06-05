<?php declare(strict_types=1);

namespace Example\Test\Http;

use Example\Http\Application;
use Example\Http\Request;
use Example\Http\Response;
use Example\Routes\Config;

class ApplicationTest extends \Example\Test\TestCase
{
    public function testGetInstance(): void
    {
        $app = Application::getInstance(new Config(['foo' => 'bar']));
        $reflection = new \ReflectionObject($app);
        $routesConfig = $reflection->getProperty('routesConfig');
        $routesConfig->setAccessible(true);
        $configValue = $routesConfig->getValue($app);

        $app2 = Application::getInstance();
        $anotherConfig = (new \ReflectionObject($app2))->getProperty('routesConfig');
        $anotherConfig->setAccessible(true);

        $this->assertSame($configValue, $anotherConfig->getValue($app2));
        $app->terminate();
    }

    public function testNoRoutesException(): void
    {
        $this->expectException(\RuntimeException::class);
        Application::getInstance()
            ->handle(new Request())
        ;
    }

    public function testRouteNotFound(): void
    {
        $config = ['/foo' => fn () => 'Some response'];
        $response = Application::getInstance(Config::configure($config))
            ->handle(new Request());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testResolveRoute(): void
    {
        $cfg = Config::configure(['/' => fn () => new Response('Hello, world')]);
        $response = Application::getInstance()
            ->handle(new Request(), $cfg);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello, world', $response->getContent());
    }
}
