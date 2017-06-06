<?php

namespace Tests;

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

}