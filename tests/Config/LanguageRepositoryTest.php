<?php

namespace Tests\Config;

use Kabas\Config\LanguageRepository;
use PHPUnit\Framework\TestCase;

class LanguageRepositoryTest extends TestCase
{

	public function setUp()
	{
		$available = ['en-GB' => ['slug' => 'en', 'label' => 'English']];
      	$default = 'en-GB';
		$this->repo = new LanguageRepository($available, $default);
	}

    /** @test */
    public function can_create_a_repository()
    {
    	$this->assertInstanceOf(LanguageRepository::class, $this->repo);
    }

    /** @test */
    public function can_register_a_new_language()
    {
        $this->repo->register('fr-BE', ['slug' => 'fr', 'label' => 'Français']);
        $this->assertCount(2, $this->repo->getAll());
        $this->assertEquals('Français', $this->repo->find('fr-BE')->label);
    }

    /** @test */
    public function can_determine_if_repository_contains_a_language()
    {
        $this->assertTrue($this->repo->has('en-GB'));
        $this->assertFalse($this->repo->has('fr-CA'));
    }

    /** @test */
    public function can_set_and_get_the_current_language()
    {
        $this->repo->set('en-GB');
        $this->assertEquals('English', $this->repo->getCurrent()->native);
    }

    /** @test */
    public function can_return_a_default_language()
    {
        $this->assertEquals('English', $this->repo->getDefault()->native);
    }

    /** @test */
    public function returns_default_if_asked_unknown_language()
    {
        $this->assertEquals('English', $this->repo->getOrDefault('fr-FR')->native);
    }

    /** @test */
    public function returns_null_if_no_current_language_is_set()
    {
        $this->assertNull($this->repo->getCurrent());
    }

    /** @test */
    public function returns_null_if_no_default_language_is_set()
    {
        $available = ['en-GB' => ['slug' => 'en', 'label' => 'English']];
        $default = '';
        $repo = new LanguageRepository($available, $default);
        $this->assertNull($repo->getDefault());
    }

}