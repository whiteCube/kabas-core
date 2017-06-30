<?php

namespace Tests;

use Kabas\Utils\Benchmark;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_start_a_named_benchmark()
    {
        Benchmark::start('test-bench');
        $this->assertTrue(is_string(Benchmark::inspect('test-bench')));
    }

    /** @test */
    public function can_start_multiple_benchmarks_simultaneously()
    {
        Benchmark::start('test-bench');
        Benchmark::start('test-bench-two');
        $this->assertTrue(is_string(Benchmark::inspect('test-bench')));
        $this->assertTrue(is_string(Benchmark::inspect('test-bench-two')));
    }

    /** @test */
    public function throws_exception_if_stopping_a_benchmark_that_does_not_exist()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(\Exception::class);
        Benchmark::stop('test-bench-three');
    }

    /** @test */
    public function can_stop_a_benchmark()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        Benchmark::start('test-bench-four');
        Benchmark::stop('test-bench-four');
        $this->expectException(\Exception::class);
        Benchmark::inspect('test-bench-four');
    }
}