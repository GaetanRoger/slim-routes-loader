<?php


namespace Gaetanroger\SlimRoutesLoader;


/**
 * Class RoutesConstants
 *
 * @author Gaetan
 * @date   28/11/2017
 */
abstract class RoutesConstants
{
    // ###
    // KEYS
    // ###
    
    
    /**
     * Key used to define a route or group pattern.
     */
    public const PATTERN_KEY = 'pattern';
    
    /**
     * Key used to define subroutes, usually used un a group.
     */
    public const ROUTES_KEY = 'routes';
    
    /**
     * Key used to define the callable called when a route or middleware is reached.
     */
    public const CALLABLE_KEY = 'callable';
    
    /**
     * Key used to define HTTP methods.
     */
    public const METHOD_KEY = 'method';
    
    /**
     * Key used to define a route name.
     */
    public const NAME_KEY = 'name';
    
    
    // ###
    // DEFAULT
    // ###
    
    /**
     * Value used as pattern if not pattern key is given.
     */
    public const PATTERN_DEFAULT_VALUE = '';
    
    /**
     * Value used as method of not method key is given.
     */
    public const METHOD_DEFAULT_VALUE = 'GET';
    
    /**
     * Value used as name if no name key is given.
     */
    public const NAME_DEFAULT_VALUE = '';
}