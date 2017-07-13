<?php

namespace Tests\Utils;

use Kabas\Utils\Log;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath(__DIR__ . DS . '..' . DS . 'TestTheme'));
        if(!is_dir(ROOT_PATH . DS . 'storage' . DS . 'logs')) mkdir(ROOT_PATH . DS . 'storage' . DS . 'logs', 0777, true);
        file_put_contents(ROOT_PATH . DS . 'storage' . DS . 'logs' . DS . 'kabas.log', '');
    }

    public function tearDown()
    {
        file_put_contents(ROOT_PATH . DS . 'storage' . DS . 'logs' . DS . 'kabas.log', '');
    }

    protected function logHas($string)
    {
        $logs = file_get_contents(ROOT_PATH . DS . 'storage' . DS . 'logs' . DS . 'kabas.log');
        $this->assertContains($string, $logs);
    }

    /** @test */
    public function can_write_errors_to_log_file()
    {
        Log::error('Unit tests - Test');
        $this->logHas('[ERROR]');
    }

    /** @test */
    public function can_write_success_to_log_file()
    {
        Log::success('Unit tests - Success');
        $this->logHas('[SUCCESS]');
    }

    /** @test */
    public function can_write_info_to_log_file()
    {
        Log::info('Unit tests - Info');
        $this->logHas('[INFO]');
    }

}