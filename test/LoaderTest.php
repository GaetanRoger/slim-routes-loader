<?php

namespace Gaetanroger\SlimRoutesLoaderTest;


use Gaetanroger\SlimRoutesLoader\Loader;
use PHPUnit\Framework\TestCase;
use Slim\App as Slim;
use Slim\Route;

class LoaderTest extends TestCase
{
    /**
     * @var Slim $slim
     */
    private $slim;

    /**
     * Return routes registered in the Slim router.
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getRoutes(): array
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
    private function assertRoute(
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

    public function testOneRoute()
    {
        $routes = [
            'pattern' => '',
            'routes' => [
                [
                    'pattern' => '/',
                    'method' => 'GET',
                    'callable' => 'testCallable',
                    'name' => 'testName'
                ]
            ]
        ];

        $loader = new Loader($routes);
        $loader($this->slim);

        $r = $this->getRoutes();

        $this->assertNotEmpty($r);
        $this->assertRoute(
            $r['route0'],
            1,
            ['GET'],
            'testName',
            'testCallable',
            '/',
            1,
            ['']
        );
    }

    public function testOneRouteInOneSubGroup()
    {
        $routes = [
            'pattern' => '',
            'routes' => [
                [
                    'pattern' => '/test',
                    'routes' => [
                        [
                            'pattern' => '/',
                            'method' => 'GET',
                            'callable' => 'testCallable',
                            'name' => 'testName'
                        ]
                    ]
                ]
            ]
        ];

        $loader = new Loader($routes);
        $loader($this->slim);

        $r = $this->getRoutes();

        $this->assertNotEmpty($r);
        $this->assertRoute(
            $r['route0'],
            1,
            ['GET'],
            'testName',
            'testCallable',
            '/test/',
            2,
            ['', '/test']
        );
    }

    public function testMultipleRoutes()
    {
        $routes = [
            'pattern' => '',
            'routes' => [
                [
                    'pattern' => '/one',
                    'method' => 'GET',
                    'callable' => 'testCallable1',
                    'name' => 'testName1'
                ],
                [
                    'pattern' => '/two',
                    'method' => 'POST',
                    'callable' => 'testCallable2',
                    'name' => 'testName2'
                ],
                [
                    'pattern' => '/three',
                    'method' => 'PUT',
                    'callable' => 'testCallable3',
                    'name' => 'testName3'
                ]
            ]
        ];

        $loader = new Loader($routes);
        $loader($this->slim);

        $r = $this->getRoutes();

        $this->assertNotEmpty($r);
        $this->assertCount(3, $r);
        $this->assertRoute(
            $r['route0'],
            1,
            ['GET'],
            'testName1',
            'testCallable1',
            '/one',
            1,
            ['']
        );
        $this->assertRoute(
            $r['route1'],
            1,
            ['POST'],
            'testName2',
            'testCallable2',
            '/two',
            1,
            ['']
        );
        $this->assertRoute(
            $r['route2'],
            1,
            ['PUT'],
            'testName3',
            'testCallable3',
            '/three',
            1,
            ['']
        );
    }

    public function testComplexe()
    {
        $routes = [
            'pattern' => '',
            'routes' => [
                [
                    'pattern' => '/one',
                    'method' => 'GET',
                    'callable' => 'testCallable1',
                    'name' => 'testName1'
                ],
                [
                    'pattern' => '/two',
                    'method' => 'POST',
                    'callable' => 'testCallable2',
                    'name' => 'testName2'
                ],
                [
                    'pattern' => '/group',
                    'routes' => [
                        [
                            'pattern' => '/one',
                            'method' => 'GET',
                            'callable' => 'testCallable1',
                            'name' => 'testName1'
                        ],
                        [
                            'pattern' => '/two',
                            'method' => 'POST',
                            'callable' => 'testCallable2',
                            'name' => 'testName2'
                        ]
                    ]
                ]
            ]
        ];

        $loader = new Loader($routes);
        $loader($this->slim);

        $r = $this->getRoutes();

        $this->assertNotEmpty($r);
        $this->assertCount(4, $r);
        $this->assertRoute(
            $r['route0'],
            1,
            ['GET'],
            'testName1',
            'testCallable1',
            '/one',
            1,
            ['']
        );
        $this->assertRoute(
            $r['route1'],
            1,
            ['POST'],
            'testName2',
            'testCallable2',
            '/two',
            1,
            ['']
        );
        $this->assertRoute(
            $r['route2'],
            1,
            ['GET'],
            'testName1',
            'testCallable1',
            '/group/one',
            2,
            ['', '/group']
        );
        $this->assertRoute(
            $r['route3'],
            1,
            ['POST'],
            'testName2',
            'testCallable2',
            '/group/two',
            2,
            ['', '/group']
        );
    }
}