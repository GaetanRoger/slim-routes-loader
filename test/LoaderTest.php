<?php

namespace Gaetanroger\SlimRoutesLoaderTest;


use Gaetanroger\SlimRoutesLoader\Loader;
use Gaetanroger\SlimRoutesLoaderTest\Mock\Logger;
use Slim\App;

class LoaderTest extends AbstractLoaderTestCase
{
    private const ONE_ROUTE = [
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
    
    public function testOneRoute()
    {
        $loader = new Loader(self::ONE_ROUTE);
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
    
    public function testComplex()
    {
        $routes = [
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
    
    public function testLogger()
    {
        $logger = new Logger();
        $slim = new App();
        
        $loader = new Loader(self::ONE_ROUTE, $logger);
        $loader($slim);
        
        $this->assertCount(1, $logger->debugs);
        $this->assertEquals('Registered new GET route: /', $logger->debugs[0]['message']);
    }
}