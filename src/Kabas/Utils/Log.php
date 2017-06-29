<?php

namespace Kabas\Utils;

class Log
{
    /**
     * The path to the log file
     * @var string
     */
    const LOGFILE = ROOT_PATH . DS . 'logs' . DS . 'kabas.log';

    /**
     * Writes a line of type INFO into the logs
     * @param string $message 
     * @return string
     */
    public static function info($message)
    {
        $string = self::buildString('INFO', $message);
        self::appendLog($string);
        return $string;
    }

    /**
     * Writes a line of type ERROR into the logs
     * @param string $message 
     * @return string
     */
    public static function error($message)
    {
        $string = self::buildString('ERROR', $message);
        self::appendLog($string);
        return $string;
    }

    /**
     * Writes a line of type SUCCESS into the logs
     * @param string $message 
     * @return string
     */
    public static function success($message)
    {
        $string = self::buildString('SUCCESS', $message);
        self::appendLog($string);
        return $string;
    }

    /**
     * Builds the string that will be saved into the log file
     * with the specified type.
     * @param string $type 
     * @param string $message 
     * @return string
     */
    protected static function buildString($type, $message)
    {
        return '['. $type .'] ' . date(DATE_RFC1123) . ' -- ' . $message . PHP_EOL;
    }

    /**
     * Adds the given message to the logs
     * @param string $message 
     * @return void
     */
    protected static function appendLog($message)
    {
        $log = self::getLog();
        $log .= $message;
        self::saveLog($log);
    }

    /**
     * Gets the contents of the old log file.
     * If it didn't exist, it creates it.
     * @return string
     */
    protected static function getLog()
    {
        if(!file_exists(self::LOGFILE)) self::saveLog('');
        return file_get_contents(self::LOGFILE);
    }

    /**
     * Writes data to the log file
     * @param string $log 
     * @return void
     */
    protected static function saveLog($log)
    {
        file_put_contents(self::LOGFILE, $log);
    }
}
