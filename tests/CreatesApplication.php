<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::driver('bcrypt')->setRounds(4);

        return $app;
    }

    protected function disableEx()
    {
      $this->app->instance(ExceptionHandler::class, new Class extends Handler {
        public function __construct() {}
        public function report(Exception $e) {}
        public function render($request, Exception $e) {
            throw $e;
          }
          public function renderForConsole($output, Exception $e) { }
      });
    }

}
