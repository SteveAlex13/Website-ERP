<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        Model::preventLazyLoading(! app()->isProduction());
        Gate::before(fn ($user): ?bool => $user->role === 'superadmin' ? true : null);

        View::composer('layouts.app', function ($view): void {
            $lowStockProducts = Product::query()
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->limit(10)
                ->get(['id', 'sku', 'name', 'stock', 'minimum_stock', 'unit']);

            $view->with([
                'notificationLowStockProducts' => $lowStockProducts,
                'notificationLowStockCount' => Product::query()->whereColumn('stock', '<=', 'minimum_stock')->count(),
            ]);
        });
    }
}
