<?php

namespace Gaetanroger\SlimRoutesLoader;


use Psr\Log\LoggerInterface;
use Slim\App as Slim;

/**
 * Load routes into Slim.
 *
 * @package Gaetanroger\SlimRoutesLoader
 */
class Loader
{
    /**
     * @var array $routes
     */
    private $routes;

    /**
     * @var LoggerInterface|null $logger
     */
    private $logger = null;

    /**
     * Loader constructor.
     * @param array $routes
     * @param null|LoggerInterface $logger
     */
    public function __construct(array $routes, ?LoggerInterface $logger = null)
    {
        $this->routes = $routes;
        $this->logger = $logger;
    }

    /**
     * Load the routes into Slim.
     *
     * @param Slim $slim
     */
    public function __invoke(Slim $slim)
    {
        $this->addGroup($this->routes, $slim);
    }

    /**
     * @param array $group
     * @param Slim $slim
     */
    private function addGroup(array $group, Slim $slim)
    {
        $loader = $this;

        $slim->group($group['pattern'], function () use ($group, $slim, $loader) {


            foreach ($group['routes'] as $route) {
                if (isset($route['routes'])) {
                    $loader->addGroup($route, $slim);
                } else {
                    $pattern = $route['pattern'];
                    $callable = $route['callable'];
                    $name = $route['name'] ?? '';

                    self::createRoute($route, $slim, $pattern, $callable, $name, $loader);
                }
            }
        });
    }

    /**
     * @param array $route
     * @param Slim $slim
     * @param string $pattern
     * @param callable|string $callable
     * @param string $name
     * @param Loader $loader
     */
    private static function createRoute(
        array $route,
        Slim $slim,
        string $pattern,
        $callable,
        string $name,
        Loader $loader): void
    {
        switch ($route['method']) {
            case 'GET':
                $slim->get($pattern, $callable)->setName($name);
                $loader->log('GET', $pattern, $callable, $name);
                break;
            case 'POST':
                $slim->post($pattern, $callable)->setName($name);
                $loader->log('POST', $pattern, $callable, $name);
                break;
            case 'PUT':
                $slim->put($pattern, $callable)->setName($name);
                $loader->log('PUT', $pattern, $callable, $name);
                break;
            case 'PATCH':
                $slim->patch($pattern, $callable)->setName($name);
                $loader->log('PATCH', $pattern, $callable, $name);
                break;
            case 'OPTIONS':
                $slim->options($pattern, $callable)->setName($name);
                $loader->log('OPTIONS', $pattern, $callable, $name);
                break;
            case 'DELETE':
                $slim->delete($pattern, $callable)->setName($name);
                $loader->log('DELETE', $pattern, $callable, $name);
                break;
            default:
                $loader->logger->warning(
                    "Route method unknown: ${$route['method']}",
                    [
                        'method' => $route['method'],
                        'pattern' => $pattern,
                        'callable' => $callable,
                        'name' => $name,
                    ]
                );
        }
    }

    /**
     * @param string $method HTTP method (GET, ...).
     * @param string $pattern URL pattern to match to call this route.
     * @param string $callable Callable to call when matching the route.
     * @param string $name Name of the route.
     */
    private function log(string $method, string $pattern, string $callable, string $name)
    {
        if ($this->logger != null) {
            $this->logger->debug(
                "Registered new $method route: $pattern",
                [
                    'method' => $method,
                    'pattern' => $pattern,
                    'callable' => $callable,
                    'name' => $name,
                ]
            );
        }
    }
}