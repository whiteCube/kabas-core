<?php

namespace Tests\Utils;

use Kabas\Utils\Options;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
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
    public function can_forward_method_calls_to_options_container()
    {
        $this->assertSame('0444/44.44.44', (string) Options::contact('phone')->label);
    }

}