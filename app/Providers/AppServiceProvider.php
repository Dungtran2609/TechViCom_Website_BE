<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Contact;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        // Chia sẻ biến $newContacts cho tất cả view trong admin
        View::composer('admin.*', function ($view) {
            $newContacts = Contact::where('is_read', false)->latest()->get();
            $view->with('newContacts', $newContacts);
        });
    }
}
