<?php 

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Kabas\Http\Request\Query;
use Kabas\Http\Request\Locale;
use Kabas\Config\LanguageRepository;

class LocaleTest extends TestCase
{
    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /**
     * Test case defined locales
     * @var Kabas\Config\LanguageRepository
     */
    protected $locales;

    /**
     * Test case defined query
     * @var Kabas\Http\Request\Query
     */
    protected $query;

    public function setUp()
    {
        $available = ['en-GB' => ['slug' => 'en', 'label' => 'English']];
        $default = 'en-GB';
        $this->locales = new LanguageRepository($available, $default);
        $this->query = new Query($this->locales, 'www.foo.com', '/bar/');
    }

    /** @test */
    public function can_define_current_language_from_query()
    {
        $this->query->setURI('/en/foo/bar/');
        $language = new Locale($this->locales, $this->query);
        $this->assertEquals('query', $language->getSource());
        $this->assertInstanceOf(\Kabas\Config\Language::class, $language->getCurrent());
        $this->assertEquals('en-GB', $language->getCurrent()->locale);
    }

    /** @test */
    public function can_define_current_language_from_cookie()
    {
        $_COOKIE[Locale::COOKIE_NAME] = 'en';
        $language = new Locale($this->locales, $this->query);
        $this->assertEquals('cookie', $language->getSource());
        $this->assertInstanceOf(\Kabas\Config\Language::class, $language->getCurrent());
        $this->assertEquals('en-GB', $language->getCurrent()->locale);
    }

    /** @test */
    public function can_define_current_language_from_browser()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr-BE,nl;q=0.8,en-GB;q=0.6,en;q=0.6,*;q=0.4';
        $language = new Locale($this->locales, $this->query);
        $this->assertEquals('browser', $language->getSource());
        $this->assertInstanceOf(\Kabas\Config\Language::class, $language->getCurrent());
        $this->assertEquals('en-GB', $language->getCurrent()->locale);
    }

    /** @test */
    public function can_define_current_language_from_language_repository_default()
    {
        $language = new Locale($this->locales, $this->query);
        $this->assertEquals('config', $language->getSource());
        $this->assertInstanceOf(\Kabas\Config\Language::class, $language->getCurrent());
        $this->assertEquals('en-GB', $language->getCurrent()->locale);
    }

    /** @test */
    public function can_suggest_a_redirect_for_better_locale_setting_in_URI()
    {
        $unsetQuery = new Query($this->locales, 'www.foo.com', '/bar/');
        $setQuery = new Query($this->locales, 'www.foo.com', 'en/bar/');
        $incorrectlySetQuery = new Query($this->locales, 'www.foo.com', 'en-GB/bar/');
        $this->assertTrue((new Locale($this->locales, $unsetQuery))->shouldRedirect());
        $this->assertFalse((new Locale($this->locales, $setQuery))->shouldRedirect());
        $this->assertTrue((new Locale($this->locales, $incorrectlySetQuery))->shouldRedirect());
    }

}
