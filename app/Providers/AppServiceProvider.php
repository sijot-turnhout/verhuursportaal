<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\StoreQuotationRequest;
use App\Actions\StoreReservationRequest;
use App\Contracts\StoreQuotation;
use App\Contracts\StoreReservation;
use App\Models\User;
use Guava\FilamentKnowledgeBase\Filament\Panels\KnowledgeBasePanel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerLaravelTelescope();
        $this->app->bind(StoreReservation::class, StoreReservationRequest::class);

        KnowledgeBasePanel::configureUsing(fn(KnowledgeBasePanel $panel) => $panel->viteTheme('resources/css/filament/knowledge-base/theme.css'));
    }

    public function boot(): void
    {
        Gate::define('viewPulse', fn(?User $user) => $user?->user_group->isWebmaster());
    }

    public function registerLaravelTelescope(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
