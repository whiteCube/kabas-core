<?php

namespace Tests\Utils;

use Kabas\Utils\Uploads;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class UploadsTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
        $this->app->loadAliases();
    }

    /** @test */
    public function can_forward_method_calls_to_uploads_container()
    {
        $this->assertFalse(Uploads::has('foo'));
    }

}