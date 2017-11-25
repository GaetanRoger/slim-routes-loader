<?php


namespace Gaetanroger\SlimRoutesLoader;


/**
 * Class LoaderInterface
 *
 * @author Gaetan
 * @date   25/11/2017
 */
abstract class LoaderInterface
{
    public abstract function load(\Slim\App $slim, bool $throwIfError = false): void;
    
    public abstract function __invoke(\Slim\App $slim, bool $throwIfError = false): void;
}