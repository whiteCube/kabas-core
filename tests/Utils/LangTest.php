<?php

namespace Tests;

use Kabas\Utils\Lang;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class LangTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_forward_method_calls_to_lang_repository()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        Lang::set('en');
        $this->assertSame('en-GB', Lang::getCurrent()->original);
    }

    /** @test */
    public function can_return_array_with_links_to_all_langs()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
        $this->assertSame('http://www.foo.com/fr/foo/bar', Lang::getMenu()[1]->url);
    }

    /** @test */
    public function can_provide_translated_strings()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
        $this->assertSame('Translation 1', Lang::trans('foo.trans'));
    }

}