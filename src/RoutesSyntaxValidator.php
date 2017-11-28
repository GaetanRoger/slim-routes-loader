<?php


namespace Gaetanroger\SlimRoutesLoader;


/**
 * Class RoutesSyntaxValidator
 *
 * @author Gaetan
 * @date   28/11/2017
 */
class RoutesSyntaxValidator
{
    private const ROUTE_TYPE = 1;
    private const GROUP_TYPE = 2;
    
    /**
     * Validate a route array syntax.
     *
     * @param array $routes Routes to validate the syntax of.
     * @return void
     */
    public static function validate(array $routes): void
    {
        $routesCount = self::assertIsAValidGroup($routes);
        self::assertHasRoutes($routesCount);
    }
    
    private static function assertIsAValidGroup(array $routes): int
    {
        if (!self::hasKey($routes, RoutesConstants::ROUTES_KEY)) {
            self::throwMissingKeyException(self::GROUP_TYPE, RoutesConstants::ROUTES_KEY, $routes);
        }
        
        $routes = $routes[RoutesConstants::ROUTES_KEY];
        
        $i = 0;
        foreach ($routes as $route) {
            try {
                $i += self::assertIsAValidRoute($route);
            } catch (\InvalidArgumentException $e) {
                $i += self::assertIsAValidGroup($route);
            }
        }
        
        return $i;
    }
    
    private static function assertIsAValidRoute(array $route): int
    {
        if (!self::hasKey($route, RoutesConstants::CALLABLE_KEY)) {
            self::throwMissingKeyException(self::ROUTE_TYPE, RoutesConstants::CALLABLE_KEY, $route);
        }
        
        return 1;
    }
    
    private static function assertHasRoutes(int $routesCount)
    {
        if ($routesCount === 0) {
            throw new \InvalidArgumentException("Not routes were found in the routes array.");
        }
    }
    
    /**
     * Checks if an array has a given string key.
     *
     * @param array  $array
     * @param string $key
     * @return bool True if the array has the key, false otherwise.
     */
    private static function hasKey(array $array, string $key): bool
    {
        return isset($array[$key]);
    }
    
    /**
     * Generate the error message and throws the missing key exception.
     *
     * An `InvalidArgumentException` is thrown and its message explains that the `$missingKey` key is missing
     * from the `$invalidType` element.
     *
     * @param int    $invalidType Group or route. Use the class constant to specify them.
     * @param string $missingKey  Key missing in the array.
     * @see RoutesSyntaxValidator::ROUTE_TYPE
     * @see RoutesSyntaxValidator::GROUP_TYPE
     */
    private static function throwMissingKeyException(int $invalidType, string $missingKey, array $element)
    {
        $type = $invalidType === self::ROUTE_TYPE ? "route" : "group";
        
        $message =
            "This element is not a valid $type element as " .
            "the \"$missingKey\" key is missing: \n" . print_r($element, true);
        
        throw new \InvalidArgumentException($message);
    }
}