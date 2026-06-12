<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App as ApplicationFacade;

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
        RedirectIfAuthenticated::redirectUsing(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.dashboard');
            }

            if (Route::has('dashboard')) {
                return route('dashboard');
            }

            return '/';
        });
        // Set application locale from cookie if present (frontend writes 'locale' or 'site_lang')
        if (! $this->app->runningInConsole()) {
            try {
                $reqLocale = request()->cookie('locale') ?? request()->cookie('site_lang') ?? null;
                if ($reqLocale && in_array($reqLocale, ['en', 'id'])) {
                    ApplicationFacade::setLocale($reqLocale);
                }
            } catch (\Exception $e) {
                // fail silently in case request() is not available in some contexts
            }
        }
    }
}
