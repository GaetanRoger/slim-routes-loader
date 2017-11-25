<?php
/**
 * Created by PhpStorm.
 * User: p1711802
 * Date: 22/11/2017
 * Time: 17:13
 */

namespace Gaetanroger\SlimRoutesLoader;


use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class YmlLoader extends Loader
{
    public function __construct(string $fileOrYml, ?LoggerInterface $logger = null)
    {
        if (is_file($fileOrYml)) {
            $fileOrYml = file_get_contents($fileOrYml);
        }
        
        $parsed = Yaml::parse($fileOrYml);
        
        parent::__construct($parsed, $logger);
    }
}