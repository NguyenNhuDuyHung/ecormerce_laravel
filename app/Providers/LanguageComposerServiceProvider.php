<?php

namespace App\Providers;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

class LanguageComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Repositories\Interfaces\LanguageRepositoryInterface', 'App\Repositories\LanguageRepository');
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Facades\View::composer('backend.dashboard.layout', function (View $view) {
            $languageRepository = $this->app->make(LanguageRepository::class);
            $languages = $languageRepository->all();
            $view->with('languages', $languages);
        });
    }
}
