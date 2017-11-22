<?php
/**
 * Created by PhpStorm.
 * User: p1711802
 * Date: 22/11/2017
 * Time: 15:35
 */

namespace Gaetanroger\SlimRoutesLoader;


use Psr\Log\LoggerInterface;

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
     * RouteLoader constructor.
     * @param array $routes
     */
    public function __construct(array $routes, ?LoggerInterface $logger = null)
    {
        $this->routes = $routes;
        $this->logger = $logger;
    }


    public function __invoke(\Slim\App $slim)
    {
        $this->addGroup($this->routes, $slim);
    }

    private function addGroup(array $group, \Slim\App $slim)
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
                                    'method'   => $route['method'],
                                    'pattern'  => $pattern,
                                    'callable' => $callable,
                                    'name'     => $name,
                                ]
                            );
                    }
                }
            }
        });
    }

    private function log(string $method, string $pattern, string $callable, string $name)
    {
        if ($this->logger != null) {
            $this->logger->debug(
                "Registered new $method route: $pattern",
                [
                    'method'   => $method,
                    'pattern'  => $pattern,
                    'callable' => $callable,
                    'name'     => $name,
                ]
            );
        }
    }
}