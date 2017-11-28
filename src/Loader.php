<?php

namespace Gaetanroger\SlimRoutesLoader;


use Psr\Log\LoggerInterface;
use Slim\App as Slim;

/**
 * Load routes into Slim.
 *
 * Please see project's README.MD for correct syntax to use when calling this class.
 *
 * @link    https://github.com/GaetanRoger/slim-routes-loader Project's README.ME.
 * @package Gaetanroger\SlimRoutesLoader
 */
class Loader extends LoaderInterface
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
     * @var bool $throwIfError
     */
    private $throwIfError;
    
    /**
     * Loader constructor.
     *
     * If given, the loader will be use to log every registered route. If an error happened (i.e. HTTP method unknown),
     * the logger will by default log it as a warning and just ignore the route. If the parameter `$throwIfError`
     * is set to true in the `load` or `__invoke` method, the problem will be logged as an error (and an exception
     * will be thrown).
     *
     * @param array                $routes
     * @param null|LoggerInterface $logger
     */
    public function __construct(array $routes, ?LoggerInterface $logger = null, bool $validateSyntaxBeforehand = true)
    {
        RoutesSyntaxValidator::validate($routes);
        $this->routes = $routes;
        $this->logger = $logger;
    }
    
    /**
     * Load the routes into Slim.
     *
     * @param Slim $slim
     * @param bool $throwIfError If `true`, an exception will be thrown if an error happen with a route and it cannot be
     *                           registered (it is ignore if set to `true`).
     */
    public function __invoke(Slim $slim, bool $throwIfError = false): void
    {
        $this->load($slim, $throwIfError);
    }
    
    /**
     * Load the routes into Slim.
     *
     * @param Slim $slim
     * @param bool $throwIfError If `true`, an exception will be thrown if an error happen with a route and it cannot be
     *                           registered (it is ignore if set to `true`).
     */
    public function load(\Slim\App $slim, bool $throwIfError = false): void
    {
        $this->throwIfError = $throwIfError;
        $this->addGroup($this->routes, $slim);
    }
    
    
    /**
     * @param array $group
     * @param Slim  $slim
     */
    private function addGroup(array $group, Slim $slim): void
    {
        $loader = $this;
        
        $slim->group($group[RoutesConstants::PATTERN_KEY], function () use ($group, $slim, $loader) {
            
            
            foreach ($group[RoutesConstants::ROUTES_KEY] as $route) {
                if (isset($route[RoutesConstants::ROUTES_KEY])) {
                    $loader->addGroup($route, $slim);
                } else {
                    $pattern = $route[RoutesConstants::PATTERN_KEY] ?? RoutesConstants::PATTERN_DEFAULT_VALUE;
                    $callable = $route[RoutesConstants::CALLABLE_KEY];
                    $name = $route[RoutesConstants::NAME_KEY] ?? RoutesConstants::NAME_DEFAULT_VALUE;
                    $method = $route[RoutesConstants::METHOD_KEY] ?? RoutesConstants::METHOD_DEFAULT_VALUE;
                    
                    self::createRoute($method, $slim, $pattern, $callable, $name, $loader);
                }
            }
        });
    }
    
    /**
     * @param array           $method
     * @param Slim            $slim
     * @param string          $pattern
     * @param callable|string $callable
     * @param string          $name
     * @param Loader          $loader
     */
    private static function createRoute(
        string $method,
        Slim $slim,
        string $pattern,
        $callable,
        string $name,
        Loader $loader
    ): void {
        switch ($method) {
            case 'GET':
                $slim->get($pattern, $callable)->setName($name);
                $loader->logRegistration('GET', $pattern, $callable, $name);
                break;
            case 'POST':
                $slim->post($pattern, $callable)->setName($name);
                $loader->logRegistration('POST', $pattern, $callable, $name);
                break;
            case 'PUT':
                $slim->put($pattern, $callable)->setName($name);
                $loader->logRegistration('PUT', $pattern, $callable, $name);
                break;
            case 'PATCH':
                $slim->patch($pattern, $callable)->setName($name);
                $loader->logRegistration('PATCH', $pattern, $callable, $name);
                break;
            case 'OPTIONS':
                $slim->options($pattern, $callable)->setName($name);
                $loader->logRegistration('OPTIONS', $pattern, $callable, $name);
                break;
            case 'DELETE':
                $slim->delete($pattern, $callable)->setName($name);
                $loader->logRegistration('DELETE', $pattern, $callable, $name);
                break;
            default:
                $message = $loader->logError($method, $pattern, $callable, $name);
                
                if ($loader->throwIfError) {
                    throw new \InvalidArgumentException($message);
                }
            
        }
    }
    
    /**
     * @param string $method   HTTP method (GET, ...).
     * @param string $pattern  URL pattern to match to call this route.
     * @param string $callable Callable to call when matching the route.
     * @param string $name     Name of the route.
     */
    private function logRegistration(string $method, string $pattern, string $callable, string $name): void
    {
        $this->log(
            'debug',
            "Registered new $method route: $pattern",
            [
                RoutesConstants::METHOD_KEY   => $method,
                RoutesConstants::PATTERN_KEY  => $pattern,
                RoutesConstants::CALLABLE_KEY => $callable,
                RoutesConstants::NAME_KEY     => $name,
            ]
        );
    }
    
    public function logError(string $method, string $pattern, string $callable, string $name): string
    {
        $message = "Route method unknown: $method";
        $context = [
            RoutesConstants::METHOD_KEY   => $method,
            RoutesConstants::PATTERN_KEY  => $pattern,
            RoutesConstants::CALLABLE_KEY => $callable,
            RoutesConstants::NAME_KEY     => $name,
        ];
        
        if ($this->throwIfError) {
            $this->log('error', $message, $context);
        } else {
            $this->log('warning', $message, $context);
        }
        
        return $message;
    }
    
    public function log(string $level, string $message, array $context = [])
    {
        if ($this->logger !== null) {
            $this->logger->log($level, $message, $context);
        }
    }
}