<?php

use Kabas\Utils\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{

    /** @test */
    public function can_convert_a_string_to_namespace_format()
    {
        $this->assertEquals('TestNamespace', Text::toNamespace('test-namespace'));
    }

    /** @test */
    public function can_remove_a_namespace_from_a_string()
    {
        $this->assertEquals('Text', Text::removeNamespace('Kabas\Utils\Text'));
    }

    /** @test */
    public function can_convert_a_string_to_a_url_friendly_slug()
    {
        $this->assertEquals('some-random-slug', Text::toSlug('Some random slug.'));
    }

}