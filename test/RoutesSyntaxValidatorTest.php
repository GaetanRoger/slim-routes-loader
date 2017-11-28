<?php


namespace Gaetanroger\SlimRoutesLoaderTest;

use Gaetanroger\SlimRoutesLoader\RoutesSyntaxValidator;


/**
 * Class RoutesSyntaxValidatorTest
 *
 * @author Gaetan
 * @date   28/11/2017
 */
class RoutesSyntaxValidatorTest extends AbstractLoaderTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEmptyRoutes()
    {
        RoutesSyntaxValidator::validate([]);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNoRoutesKeyInRootElement()
    {
        RoutesSyntaxValidator::validate([
                'pattern' => '/',
                'hello'   => 'hey',
            ]
        );
    }
    
    public function testValidOneRoute()
    {
        RoutesSyntaxValidator::validate(self::ONE_ROUTE);
        
        $this->assertTrue(true);
    }
    
    public function testValidOneRouteInSubGroup()
    {
        RoutesSyntaxValidator::validate(self::ONE_ROUTE_IN_SUBGROUP);
        
        $this->assertTrue(true);
    }
    
    public function testValidMultipleRoutes()
    {
        RoutesSyntaxValidator::validate(self::MULTIPLE_ROUTES);
        
        $this->assertTrue(true);
    }
    
    public function testValidComplexRoutes()
    {
        RoutesSyntaxValidator::validate(self::COMPLEX_ROUTES);
        
        $this->assertTrue(true);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOneRoute()
    {
        RoutesSyntaxValidator::validate([
            'routes' => [
                [
                    'pattern' => '/',
                    // missing callable key
                    'name'    => 'name',
                ],
            ],
        ]);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSubGroup()
    {
        RoutesSyntaxValidator::validate([
            'routes' => [
                [
                    'wrongKey' => [
                        [
                            'pattern'  => '/',
                            'callable' => 'callable',
                            'name'     => 'name',
                        ],
                    ],
                ],
            ],
        ]);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMultipleRoute()
    {
        RoutesSyntaxValidator::validate([
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
                    'pattern' => '/three',
                    'method'  => 'PUT',
                    // missing callable
                    'name'    => 'testName3',
                ],
            ],
        ]);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNoRoutes()
    {
        RoutesSyntaxValidator::validate([
            'routes' => [
                [
                    'routes' => [
                        [
                            'routes' => [
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
    
}