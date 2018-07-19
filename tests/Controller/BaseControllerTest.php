<?php 

namespace Tests\Controllers;

use Kabas\App;
use Kabas\Content\Pages\Item;
use Kabas\Objects\Page\Title;
use Tests\CreatesApplication;
use Kabas\Http\Responses\Json;
use PHPUnit\Framework\TestCase;
use \Kabas\Http\Responses\View;
use Kabas\Http\Responses\Redirect;
use Kabas\Controller\BaseController;
use Kabas\Utils\Benchmark;

class BaseControllerTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $this->catch(function(){
            $this->controller = new BaseController(App::content()->pages->get('about'));
        });
    }

    /** @test */
    public function can_set_and_get_data()
    {
        $this->controller->foo = 'bar';
        $this->assertSame('bar', $this->controller->foo);
    }

    /** @test */
    public function returns_false_when_calling_method_that_does_not_exist()
    {
        $this->assertFalse($this->controller->foo());
    }

    /** @test */
    public function can_return_a_redirect_response()
    {
        $this->assertInstanceOf(Redirect::class, $this->controller->redirect('home'));
    }

    /** @test */
    public function can_return_a_view_response()
    {
        $this->assertInstanceOf(View::class, $this->controller->view('header', []));
    }

    /** @test */
    public function can_return_a_json_response()
    {
        $this->assertInstanceOf(Json::class, $this->controller->json([]));
    }

    /** @test */
    public function can_pass_method_calls_to_underlying_item()
    {
        $this->assertInstanceOf(Title::class, $this->controller->getTitle());
    }

}