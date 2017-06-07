<?php

namespace Tests\Config;

use WhiteCube\Lingua\Service as Lingua;
use Kabas\Config\Language;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{

	public function setUp()
	{
		$arguments = ['slug' => 'en', 'label' => 'English'];
      	$locale = 'en-GB';
		$this->lang = new Language($locale, $arguments);
	}

    /** @test */
    public function can_be_instanciated_from_a_locale()
    {
        $this->assertInstanceOf(Language::class, $this->lang);
    }

    /** @test */
    public function can_be_set_as_active_locale()
    {
        $this->assertFalse($this->lang->isCurrent);
        $this->lang->activate();
        $this->assertTrue($this->lang->isCurrent);
    }

    /** @test */
    public function returns_false_if_incorrect_locale_format()
    {
        $lang = new Language('x-BE', []);
        $this->assertFalse($lang->locale);
    }

    /** @test */
    public function can_find_slug_on_its_own_if_it_was_not_specified()
    {
        $lang = new Language('en-GB', ['label' => 'English']);
        $this->assertEquals('en', $lang->slug);
    }

    /** @test */
    public function can_find_label_on_its_own_if_it_was_not_specified()
    {
        $lang = new Language('en-GB', ['slug' => 'en']);
        $this->assertEquals('English (United Kingdom)', $lang->label);
    }

}