<?php


namespace Gaetanroger\SlimRoutesLoaderTest;

use Gaetanroger\SlimRoutesLoader\YmlLoader;


/**
 * Class YmlLoaderTest
 *
 * @author Gaetan
 * @date   25/11/2017
 */
class YmlLoaderTest extends AbstractLoaderTestCase
{
    public function testOneRoute()
    {
        $yml = '---
pattern:
routes:
  - pattern: /
    method: GET
    callable: testCallable
    name: testName';
        
        $loader = new YmlLoader($yml);
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
        $yml = '---
pattern:
routes:
  - pattern: /test
    routes:
      - pattern: /
        method: GET
        callable: testCallable
        name: testName';
        
        $loader = new YmlLoader($yml);
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
        $yml = '
pattern:
routes:
  - pattern: /one
    method: GET
    callable: testCallable1
    name: testName1
  - pattern: /two
    method: POST
    callable: testCallable2
    name: testName2
  - pattern: /three
    method: PUT
    callable: testCallable3
    name: testName3';
        
        $loader = new YmlLoader($yml);
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
    
    public function testComplexInFile()
    {
        $yml = '
pattern:
routes:
  - pattern: /one
    method: GET
    callable: testCallable1
    name: testName1
  - pattern: /two
    method: POST
    callable: testCallable2
    name: testName2
  - pattern: /group
    routes:
      - pattern: /one
        method: GET
        callable: testCallable1
        name: testName1
      - pattern: /two
        method: POST
        callable: testCallable2
        name: testName2';
        
        
        $filename = __DIR__ . '/tmp.yml';
        file_put_contents($filename, $yml);
        
        $loader = new YmlLoader($filename);
        $loader($this->slim);
        
        unlink($filename);
        
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