<?php 

namespace Tests;

use Kabas\App;

trait CreatesApplication {
	public function createApplication()
	{
		$kabas = new App(__DIR__ . '/Mocking/public');
		return $kabas;
	}
}