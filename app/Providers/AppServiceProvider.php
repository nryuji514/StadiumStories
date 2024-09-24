<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Store;

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
        Paginator::useBootstrap();
        \URL::forceScheme('https');
        $this->app['request']->server->set('HYYPS','on');
        view()->composer('*', function ($view) {
            // 必要に応じてここでデフォルトのStoreを取得
            $store = Store::first(); // Storeのデフォルト値やログインユーザーに関連した値に変更可能

            $view->with('store', $store);
        });
    }
}
