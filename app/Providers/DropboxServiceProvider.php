<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      Storage::extend('dropbox', function ($app, $config) {
         $client = new DropboxClient(
             $config['authorizationToken']
         );

         return new Filesystem(new DropboxAdapter($client));
     });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
