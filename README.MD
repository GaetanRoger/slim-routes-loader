# Slim routes loader
[![Build Status](https://travis-ci.org/GaetanRoger/slim-routes-loader.svg?branch=master)](https://travis-ci.org/GaetanRoger/slim-routes-loader)

When using [Slim PHP Framework](https://www.slimframework.com/),
you need to register your routes. This library allows you to do so
via a simple config file.
## How to install
Using composer is the easiest way.
```
composer require gaetanroger/slim-routes-loader
```
You can always get the sources from this repo and install
it manually in your project.

## How to use
*For more info about the syntax to follow, see [Syntax and rules](#syntax-and-rules).*
### Regular PHP array format
Write your routes using a regular PHP associative array,
then use the `Loader` class to import it.
```php
$routes = [
    'pattern' => '',
    'routes'  => [
        [
            'pattern'  => '/',
            'method'   => 'GET',
            'callable' => 'myCallable',
            'name'     => 'optianalName',
        ],
    ],
];
$slim = new \Slim\App();

$loader = new Gaetanroger\SlimRoutesLoader\Loader($routes);
$loader->load($slim); // or $loader($slim)
```
If you want to store your routes in another file, simply include them
using a regular PHP `require`.
```php
$routes = require __DIR__ '/myRoutes.php';
$slim = new \Slim\App();

$loader = new \Gaetanroger\SlimRoutesLoader\Loader($routes);
$loader($slim); // or $loader->load($slim)
```
### Json format
If you prefer to write your routes as Json, simply use the `JsonLoader`.
```json
{
    "pattern": "",
    "routes": [
        {
            "pattern": "/",
            "method": "GET",
            "callable": "testCallable",
            "name": "testName"
        }
    ]
}
```
```php
$slim = new \Slim\App();

$loader = new \Gaetanroger\SlimRoutesLoader\JsonLoader($json);
$loader($slim); // or $loader->load($slim)
```
The `$json` variable can contain a json string or the path to a json file containing 
the routes.
### Yaml format
If you prefer to write your routes as Yaml, simply use the `YmlLoader`.
```yaml
pattern:
routes:
  - pattern: /
    method: GET
    callable: testCallable
    name: testName
```
```php
$slim = new \Slim\App();

$loader = new \Gaetanroger\SlimRoutesLoader\YmlLoader($yml);
$loader($slim); // or $loader->load($slim)
```
The `$yml` variable can contain a Yaml string or the path to a Yaml file containing 

### Syntax and rules
No matter what format you choose to use, the loaders require a certain syntax.

Please see the [Slim documentation](https://www.slimframework.com/docs/objects/router.html) 
to get more info about specific points.

#### Route syntax
* `pattern`: the route pattern
* `method`: the HTTP method the route is waiting for (GET, POST, etc.)
* `callable`: the function/method/invokable to be called when the route is reached
* `name`: (optional) the name of the route

#### Group syntax
* `pattern`: the group pattern
* `routes`: an array containing other groups or routes

#### General rules
* The top element must be a group (usually with an empty pattern unless you want to prefix all your routes)
* A pattern can be left empty.

## Examples
### PHP array syntax
```php
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
```
### Json syntax
```json
{
    "pattern": "",
    "routes": [
        {
            "pattern": "/one",
            "method": "GET",
            "callable": "testCallable1",
            "name": "testName1"
        },
        {
            "pattern": "/two",
            "method": "POST",
            "callable": "testCallable2",
            "name": "testName2"
        },
        {
            "pattern": "/group",
            "routes": [
                {
                    "pattern": "/one",
                    "method": "GET",
                    "callable": "testCallable1",
                    "name": "testName1"
                },
                {
                    "pattern": "/two",
                    "method": "POST",
                    "callable": "testCallable2",
                    "name": "testName2"
                }
            ]
        }
    ]
}
```
### Yaml syntax
```yaml
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
```