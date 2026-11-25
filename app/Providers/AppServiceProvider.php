<?php

declare(strict_types=1);

namespace App\Providers;

use App\Catalog\CatalogRepository;
use App\Catalog\JsonCatalogRepository;
use App\ViewModels\AssistantViewModel;
use App\ViewModels\BentoViewModel;
use App\ViewModels\CategoryFilterViewModel;
use App\ViewModels\HeroViewModel;
use App\ViewModels\InterpreterViewModel;
use App\ViewModels\SeoViewModel;
use App\ViewModels\TrustStripViewModel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

final class AppServiceProvider extends ServiceProvider
{
    private const array VIEW_MODELS = [
        'sections.hero' => ['hero', HeroViewModel::class],
        'partials.filter' => ['filter', CategoryFilterViewModel::class],
        'sections.assistant' => ['assistant', AssistantViewModel::class],
        'sections.bento' => ['bento', BentoViewModel::class],
        'sections.interpreter' => ['interpreter', InterpreterViewModel::class],
        'partials.trust-strip' => ['trust', TrustStripViewModel::class],
        'layouts.app' => ['seo', SeoViewModel::class],
    ];

    public function register(): void
    {
        $this->app->singleton(CatalogRepository::class, fn (): CatalogRepository => new JsonCatalogRepository(resource_path('data/catalog.json')));
    }

    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/partials'));

        foreach (self::VIEW_MODELS as $view => [$name, $class]) {
            View::composer($view, fn (ViewInstance $instance) => $instance->with($name, $this->app->make($class)));
        }
    }
}
