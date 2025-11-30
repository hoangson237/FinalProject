<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- KHÔNG ĐƯỢC THIẾU DÒNG NÀY

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
        // DÒNG NÀY GIÚP PHÂN TRANG ĐẸP & NHỎ GỌN
        Paginator::useBootstrapFive(); 
    }
}