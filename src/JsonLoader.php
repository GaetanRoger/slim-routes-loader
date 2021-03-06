<?php
/**
 * Created by PhpStorm.
 * User: p1711802
 * Date: 22/11/2017
 * Time: 17:11
 */

namespace Gaetanroger\SlimRoutesLoader;


use Psr\Log\LoggerInterface;

/**
 * Class JsonLoader
 *
 * Please see project's README.MD for correct syntax to use when calling this class.
 *
 * @link    https://github.com/GaetanRoger/slim-routes-loader Project's README.ME.
 * @package Gaetanroger\SlimRoutesLoader
 */
class JsonLoader extends Loader
{
    public function __construct(string $fileOrJson, ?LoggerInterface $logger = null)
    {
        if (is_file($fileOrJson)) {
            $fileOrJson = file_get_contents($fileOrJson);
        }
        
        $decoded = json_decode($fileOrJson, true);
        
        if ($decoded === null) {
            throw new \InvalidArgumentException("JsonLoader is not able to parse the json file/string to PHP arrays. The Json is invalid or too deep.");
        }
        
        parent::__construct($decoded, $logger);
    }
}