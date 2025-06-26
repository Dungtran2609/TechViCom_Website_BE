<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Lienhe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            $newContacts = Lienhe::where('is_read', false)->latest()->take(5)->get();
            $view->with('newContacts', $newContacts);
        });
    }

    
}