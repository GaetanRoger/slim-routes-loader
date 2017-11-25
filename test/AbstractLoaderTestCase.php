<?php
/**
 * Created by PhpStorm.
 * User: p1711802
 * Date: 22/11/2017
 * Time: 17:15
 */

namespace Gaetanroger\SlimRoutesLoaderTest;


use PHPUnit\Framework\TestCase;
use Slim\App as Slim;
use Slim\Route;

class AbstractLoaderTestCase extends TestCase
{
    /**
     * @var Slim $slim
     */
    protected $slim;

    /**
     * Return routes registered in the Slim router.
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getRoutes(): array
    {
        return $this->slim->getContainer()->get('router')->getRoutes();
    }

    /**
     * Assert values of a Route object.
     *
     * @param Route $route
     * @param int $methodsCount
     * @param array $methods
     * @param string $name
     * @param string $callable
     * @param string $pattern
     * @param int $groupsCount
     * @param array $groupsPatterns
     */
    protected function assertRoute(
        Route $route,
        int $methodsCount,
        array $methods,
        string $name,
        string $callable,
        string $pattern,
        int $groupsCount,
        array $groupsPatterns)
    {
        $this->assertCount($methodsCount, $route->getMethods());

        foreach ($methods as $method) {
            $this->assertContains($method, $route->getMethods());
        }

        $this->assertEquals($name, $route->getName());
        $this->assertEquals($callable, $route->getCallable());
        $this->assertEquals($pattern, $route->getPattern());
        $this->assertCount($groupsCount, $route->getGroups());

        foreach ($route->getGroups() as $group) {
            $this->assertContains($group->getPattern(), $groupsPatterns);
        }
    }

    protected function setUp()
    {
        $this->slim = new Slim();
    }
}