<?php

namespace Tests;

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

    /** @test */
    public function can_uppercase()
    {
        $this->assertSame('MY FOO TITLE', Text::uppercase('My foo title'));
    }

    /** @test */
    public function can_be_lowercased()
    {
        $this->assertSame('my foo title', Text::lowercase('My foo title'));
    }

    /** @test */
    public function can_escape_html_entities()
    {
        $this->assertSame('&lt;foo&gt;', Text::escape('<foo>'));;
    }

    /** @test */
    public function can_check_if_value_contains_a_string()
    {
        $this->assertFalse(Text::contains('My foo title', 'Foo'));
        $this->assertTrue(Text::contains('My foo title', 'Foo', false));
        $this->assertFalse(Text::contains('My foo title', 'bar'));
    }

    /** @test */
    public function can_cut_value_and_append_triple_dots()
    {
        $this->assertSame('My foo tit&nbsp;&hellip;', Text::cut('My foo title', 10));
    }

    /** @test */
    public function can_cut_value_and_append_triple_dots_without_cutting_into_a_word()
    {
        $this->assertSame('My foo&nbsp;&hellip;', Text::shorten('My foo title', 10));
    }

    /** @test */
    public function can_check_if_value_is_longer_than_given_length()
    {
        $this->assertTrue(Text::exceeds('My foo title', 5));
        $this->assertFalse(Text::exceeds('My foo title', 100));
    }

}