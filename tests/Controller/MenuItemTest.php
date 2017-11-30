<?php 

namespace Tests\Controllers;

use Kabas\App;
use Tests\CreatesApplication;
use Kabas\Controller\MenuItem;
use Kabas\Content\Menus\Link;
use PHPUnit\Framework\TestCase;

class MenuItemTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/about');
        $this->catch(function(){
            $this->menu = App::content()->menus->get('main');
            $this->controller = new MenuItem($this->menu->items->items[0]);
        });
    }

    /** @test */
    public function can_set_and_get_data()
    {
        $this->controller->foo = 'bar';
        $this->assertSame('bar', $this->controller->foo);
    }

    /** @test */
    public function can_test_if_contains_subitems()
    {
        $this->assertFalse($this->controller->hasSub());
        $withSubitems = new MenuItem($this->menu->items->items[2]);
        $this->assertTrue($withSubitems->hasSub());
    }

    /** @test */
    public function can_return_subitems()
    {
        $this->assertCount(0, $this->controller->getSub());
        $withSubitems = new MenuItem($this->menu->items->items[2]);
        $this->assertCount(1, $withSubitems->getSub());
    }

    /** @test */
    public function can_check_if_current_target_is_an_existing_page()
    {
        $this->assertFalse($this->controller->isActive());
        $withSubitems = new MenuItem($this->menu->items->items[4]);
        $this->assertFalse($withSubitems->isActive(false));
        $this->assertTrue($withSubitems->isActive(true));
    }

    /** @test */
    public function can_check_if_url_is_current_page()
    {
        $this->setPageRoute('/optional/foo');
        $this->app->router->load()->setCurrent();
        $items = [
            'root' => (object) ['label' => 'foo', 'target' => 'http://www.foo.com/optional'],
            'wrong' => (object) ['label' => 'foo', 'target' => 'http://www.foo.com/optional/bar'],
            'right' => (object) ['label' => 'foo', 'target' => 'http://www.foo.com/optional/foo'],
            'full' => (object) ['label' => 'foo', 'target' => 'http://www.foo.com/optional/foo/bar'],
            'over' => (object) ['label' => 'foo', 'target' => 'http://www.foo.com/optional/foo/bar/test']
        ];
        foreach ($items as $key => $value) {
            $items[$key] = new MenuItem(new Link($value, $key, $this->menu->getStructure()->item));
        }
        $this->assertTrue($items['root']->isActive());
        $this->assertFalse($items['wrong']->isActive());
        $this->assertTrue($items['right']->isActive());
        $this->assertFalse($items['full']->isActive());
        $this->assertFalse($items['over']->isActive());
    }

    /** @test */
    public function returns_hash_if_menu_does_not_contain_url_field()
    {
        $menu = App::content()->menus->get('test');
        $controller = new MenuItem($menu->items->items[0]);
        $this->assertSame('#', $controller->url);
    }

}