<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Resources\LeaseResource\Widgets\LatestReservationRequests;
use App\Filament\Resources\LeaseResource\Widgets\StatsOverview;
use App\Filament\Resources\QuotationResource\Widgets\LastestQuotationRequestsTable;
use App\Filament\Resources\UtilityResource\Widgets\UtilityUsageWidget;
use App\Filament\Widgets\IncomeStatisticsWidget;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->topNavigation()
            ->login()
            ->profile()
            ->font('Open sans')
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->colors([
                'danger' => '#d9534f',
                'gray' => [
                    50 => '#f6f6f6',
                    100 => '#e7e7e7',
                    200 => '#d1d1d1',
                    300 => '#b0b0b0',
                    400 => '#888888',
                    500 => '#666666',
                    600 => '#5d5d5d',
                    700 => '#4f4f4f',
                    800 => '#454545',
                    900 => '#3d3d3d',
                    950 => '#262626',
                ],
                'info' => '#5bc0de',
                'primary' => '#709553',
                'success' => '#5cb85c',
                'warning' => '#f0ad4e',
            ])
            ->renderHook(
                // PanelsRenderHook::BODY_END,
                PanelsRenderHook::FOOTER,
                fn() => view('footer'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->brandLogo(asset('img/sijot.png'))
            ->favicon(asset('img/favicon/favicon.ico'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                IncomeStatisticsWidget::class,
                StatsOverview::class,
                UtilityUsageWidget::class,
                LatestReservationRequests::class,
                LastestQuotationRequestsTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                KnowledgeBasePlugin::make()->modalPreviews()->slideOverPreviews()->disableKnowledgeBasePanelButton(),
                EnvironmentIndicatorPlugin::make(),
                QuickCreatePlugin::make()->excludes([
                    \App\Filament\Resources\ContactSubmissionResource::class,
                    \App\Filament\Resources\InvoiceResource::class,
                    \App\Filament\Resources\QuotationResource::class,
                ]),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(config('app.debug'))
                    ->users([
                        'leiding' => 'leiding@domain.tld',
                        'raad van bestuur' => 'rvb@domain.tld',
                        'vzw' => 'vzw@domain.tld',
                        'webmaster' => 'webmaster@domain.tld',
                    ]),
            ]);
    }
}
