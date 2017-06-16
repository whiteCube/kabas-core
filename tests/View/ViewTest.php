<?php 

namespace Tests\View;

use Kabas\View\View;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        ob_start();
        $this->view = new View('params', [], 'templates');
        $this->result = ob_get_clean();
    }

    /** @test */
    public function can_determine_if_first_view()
    {
        $this->assertFalse(View::isFirstView($this->view));
    }

    /** @test */
    public function can_make_an_instance_of_itself()
    {
        ob_start();
        $result = View::make('params', [], 'templates');
        ob_get_clean();
        $this->assertInstanceOf(View::class, $result);
    }

    /** @test */
    public function can_return_a_404()
    {
        $this->expectOutputRegex('/404/');
        View::notFound();
    }

}