<?php


namespace Gaetanroger\SlimRoutesLoaderTest\Mock;

use Psr\Log\LoggerInterface;


/**
 * Class Logger
 *
 * @author Gaetan
 * @date   25/11/2017
 */
class Logger implements LoggerInterface
{
    /**
     * @var array $emergencies
     */
    public $emergencies = [];
    
    /**
     * @var array $alerts
     */
    public $alerts = [];
    
    /**
     * @var array $criticals
     */
    public $criticals = [];
    
    /**
     * @var array $errors
     */
    public $errors = [];
    
    /**
     * @var array $warnings
     */
    public $warnings = [];
    
    /**
     * @var array $notices
     */
    public $notices = [];
    
    /**
     * @var array $infos
     */
    public $infos = [];
    
    /**
     * @var array $debugs
     */
    public $debugs = [];
    
    /**
     * @var array $logs
     */
    public $logs = [];
    
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->emergencies[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->alerts[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->criticals[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->errors[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->warnings[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->notices[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->infos[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->debugs[] = [
            'message' => $message,
            'context' => $context,
        ];
    }
    
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->logs[] = [
            'level'   => $level,
            'message' => $message,
            'context' => $context,
        ];
    }
}