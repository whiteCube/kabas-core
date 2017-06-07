<?php 

namespace Tests;

use Kabas\App;

trait CreatesApplication {

	protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

	public function createApplication()
	{
		$kabas = new App(__DIR__ . '/TestTheme/public');
		return $kabas;
	}
}