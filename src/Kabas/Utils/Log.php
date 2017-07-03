<?php

namespace Kabas\Utils;

class Log
{
    /**
     * The path to the log folder
     * @var string
     */
    const LOGFOLDER = ROOT_PATH . DS . 'storage' . DS . 'logs' . DS;

    /**
     * Writes a line of type INFO into the logs
     * @param string $message 
     * @return string
     */
    public static function info($message, $logfile = 'kabas.log')
    {
        $string = self::buildString('INFO', $message);
        self::appendLog($string, $logfile);
        return $string;
    }

    /**
     * Writes a line of type ERROR into the logs
     * @param string $message 
     * @return string
     */
    public static function error($message, $logfile = 'kabas.log')
    {
        $string = self::buildString('ERROR', $message);
        self::appendLog($string, $logfile);
        return $string;
    }

    /**
     * Writes a line of type SUCCESS into the logs
     * @param string $message 
     * @return string
     */
    public static function success($message, $logfile = 'kabas.log')
    {
        $string = self::buildString('SUCCESS', $message);
        self::appendLog($string, $logfile);
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
    protected static function appendLog($message, $logfile)
    {
        $log = self::getLog($logfile);
        $log .= $message;
        self::saveLog($log, $logfile);
    }

    /**
     * Gets the contents of the old log file.
     * If it didn't exist, it creates it.
     * @return string
     */
    protected static function getLog($file)
    {
        if(!file_exists(self::LOGFOLDER . $file)) self::saveLog('', $file);
        return file_get_contents(self::LOGFOLDER . $file);
    }

    /**
     * Writes data to the log file
     * @param string $log 
     * @return void
     */
    protected static function saveLog($log, $file)
    {
        if(!is_dir(self::LOGFOLDER)) mkdir(self::LOGFOLDER, 0777, true);
        file_put_contents(self::LOGFOLDER . $file, $log);
    }
}
