<?php

namespace Tests\Objects\Uploads;

use \Mockery as M;
use Kabas\Objects\Uploads\Item;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\NotFoundException;

class ItemTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('DEBUG')) define('DEBUG', true);
        if(!defined('PUBLIC_PATH')) define('PUBLIC_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS . 'TestTheme' . DS . 'public'));
        $mover = M::mock('Kabas\Objects\Uploads\UploadMover');
        $mover->shouldReceive('move')->andReturn(true);
        $mover->shouldReceive('copy')->andReturn(true);
        $this->item = new Item('foo', [
            'size' => 424242,
            'tmp_name' => 'abc.png',
            'name' => 'Foo.png'
        ], $mover);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Item::class, $this->item);
    }

    /** @test */
    public function can_get_a_piece_of_data()
    {
        $this->assertSame(424242, $this->item->size);
    }

    /** @test */
    public function can_return_the_path_to_the_saved_upload()
    {
        $this->assertNull($this->item->src());
        $this->item->save();
        $this->assertTrue(is_string($this->item->src()));
    }

    /** @test */
    public function can_copy_a_file_after_saving_it()
    {
        $this->item->save();
        $this->assertInstanceOf(Item::class, $this->item->copy('copied.png'));
    }

    /** @test */
    public function throws_exception_when_trying_to_copy_file_that_was_not_saved()
    {
        $this->expectException(NotFoundException::class);
        $this->item->copy('copied.png');
    }

    /** @test */
    public function can_get_extension()
    {
        $this->assertSame('png', $this->item->getExtension());
    }

    public function tearDown() {
        M::close();
    }

}