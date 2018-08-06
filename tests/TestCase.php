<?php

namespace Tests;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    // use DatabaseMigrations;
    

    protected function setUp()
    {
        parent::setUp();
        DB::statement('PRAGMA foreign_keys=on;');
        $this->disableExceptionHandling();
    }

    protected function signIn($user = null) {
        $user = $user ?? create('App\User');

        $this->actingAs($user);

        return $this;
    }

    // Hat tip, @adamwathan.
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}

