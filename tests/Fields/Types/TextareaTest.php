<?php 

namespace Tests\Fields\Types;

use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Textarea;
use Kabas\Exceptions\TypeException;

class TextareaTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = "Text";
        $data->type = "text";
        $this->textarea = new Textarea('text', $data);
        $this->textarea->set('My foo text');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Textarea::class, $this->textarea);
    }

}