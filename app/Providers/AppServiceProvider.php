<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    //   if(url('/')!= 'https://sisukma.bengkaliskab.go.id')
        // return redirect('https://sisukma.bengkaliskab.go.id'.(strlen(request()->path())>1 ? '/'.request()->path() : ''))->send();
    }
}
