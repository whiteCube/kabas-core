<?php 

namespace Tests\View;

use Kabas\View\View;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    use CreatesApplication;

    public $view;
    public $result;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
        ]);
        $this->app->config->get('site.theme');
        $this->result = $this->catch(function(){
            $this->view = new View('params', [], 'templates');
        });
    }

    /** @test */
    public function can_determine_if_first_view()
    {
        $this->assertFalse(View::isFirstView($this->view));
    }

    /** @test */
    public function can_make_an_instance_of_itself()
    {
        $result = $this->catch(function(){
            return View::make('params', [], 'templates');
        }, true);
        $this->assertInstanceOf(View::class, $result);
    }

    /** @test */
    public function can_return_a_404()
    {
        $this->expectOutputRegex('/404/');
        View::notFound();
    }

}