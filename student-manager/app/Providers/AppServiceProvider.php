<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- Quan trọng: Import thư viện phân trang

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
        // Sử dụng Bootstrap 5 để hiển thị phân trang (Khắc phục lỗi giao diện vỡ)
        Paginator::useBootstrapFive();
    }
}