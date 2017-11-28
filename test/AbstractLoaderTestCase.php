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
    protected const ONE_ROUTE = [
        'pattern' => '',
        'routes'  => [
            [
                'pattern'  => '/',
                'method'   => 'GET',
                'callable' => 'testCallable',
                'name'     => 'testName',
            ],
        ],
    ];
    
    protected const ONE_ROUTE_IN_SUBGROUP = [
        'pattern' => '',
        'routes'  => [
            [
                'pattern' => '/test',
                'routes'  => [
                    [
                        'pattern'  => '/',
                        'method'   => 'GET',
                        'callable' => 'testCallable',
                        'name'     => 'testName',
                    ],
                ],
            ],
        ],
    ];
    
    protected const MULTIPLE_ROUTES = [
        'pattern' => '',
        'routes'  => [
            [
                'pattern'  => '/one',
                'method'   => 'GET',
                'callable' => 'testCallable1',
                'name'     => 'testName1',
            ],
            [
                'pattern'  => '/two',
                'method'   => 'POST',
                'callable' => 'testCallable2',
                'name'     => 'testName2',
            ],
            [
                'pattern'  => '/three',
                'method'   => 'PUT',
                'callable' => 'testCallable3',
                'name'     => 'testName3',
            ],
        ],
    ];
    
    protected const COMPLEX_ROUTES = [
        'pattern' => '',
        'routes'  => [
            [
                'pattern'  => '/one',
                'method'   => 'GET',
                'callable' => 'testCallable1',
                'name'     => 'testName1',
            ],
            [
                'pattern'  => '/two',
                'method'   => 'POST',
                'callable' => 'testCallable2',
                'name'     => 'testName2',
            ],
            [
                'pattern' => '/group',
                'routes'  => [
                    [
                        'pattern'  => '/one',
                        'method'   => 'GET',
                        'callable' => 'testCallable1',
                        'name'     => 'testName1',
                    ],
                    [
                        'pattern'  => '/two',
                        'method'   => 'POST',
                        'callable' => 'testCallable2',
                        'name'     => 'testName2',
                    ],
                ],
            ],
        ],
    ];
    
    
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
     * @param Route  $route
     * @param int    $methodsCount
     * @param array  $methods
     * @param string $name
     * @param string $callable
     * @param string $pattern
     * @param int    $groupsCount
     * @param array  $groupsPatterns
     */
    protected function assertRoute(
        Route $route,
        int $methodsCount,
        array $methods,
        string $name,
        string $callable,
        string $pattern,
        int $groupsCount,
        array $groupsPatterns
    ) {
        $this->assertCount($methodsCount, $route->getMethods(),
            "Routes count does not match $methodsCount");
        
        foreach ($methods as $method) {
            $this->assertContains($method, $route->getMethods(),
                "Method $method was not found in route's methods");
        }
        
        $this->assertEquals($name, $route->getName(),
            "Name of the route {$route->getName()} does not match $name.");
        
        $this->assertEquals($callable, $route->getCallable(),
            "Callable of the route {$route->getCallable()} does not match $callable.");
        
        $this->assertEquals($pattern, $route->getPattern(),
            "Pattern of the route {$route->getPattern()} does not match $pattern.");
        
        $this->assertCount($groupsCount, $route->getGroups(),
            "Route's groups count does not match $groupsCount");
        
        foreach ($route->getGroups() as $group) {
            $this->assertContains($group->getPattern(), $groupsPatterns,
                "Group pattern {$group->getPattern()} not found in route's groups patterns.");
        }
    }
    
    protected function setUp()
    {
        $this->slim = new Slim();
    }
}