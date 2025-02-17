<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\App\Profile;
use App\Filament\Resources\LeaseResource\Widgets\LatestReservationRequests;
use App\Filament\Resources\LeaseResource\Widgets\StatsOverview;
use App\Filament\Resources\QuotationResource\Widgets\LastestQuotationRequestsTable;
use App\Filament\Resources\UtilityResource\Widgets\UtilityUsageWidget;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Config;
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
            ->passwordReset()
            ->profile(Profile::class, false)
            ->font('Open sans')
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->renderHook(
                // PanelsRenderHook::BODY_END,
                PanelsRenderHook::FOOTER,
                fn() => view('footer'),
            )
            ->userMenuItems([
                MenuItem::make()
                    ->label(trans('Documentatie'))
                    ->icon('heroicon-o-book-open')
                    ->url('https://sijot-turnhout.github.io/verhuur-portaal-documentatie/')
                    ->openUrlInNewTab(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->brandLogo(asset('img/sijot.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('img/favicon/favicon.ico'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->widgets([
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
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                AuthUIEnhancerPlugin::make()->emptyPanelBackgroundImageUrl(asset('img/sgv-wit.png'))->emptyPanelBackgroundColor(Color::hex('#826644')),
                KnowledgeBasePlugin::make()->modalPreviews()->slideOverPreviews()->disableKnowledgeBasePanelButton(),
                EnvironmentIndicatorPlugin::make(),
                QuickCreatePlugin::make()->excludes([
                    \App\Filament\Resources\ContactSubmissionResource::class,
                    \App\Filament\Resources\InvoiceResource::class,
                    \App\Filament\Resources\QuotationResource::class,
                    \App\Filament\Clusters\Billing\Resources\DepositResource::class,
                    \App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource::class,
                ]),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(Config::boolean('app.debug', false))
                    ->users([
                        'leiding' => 'leiding@domain.tld',
                        'raad van bestuur' => 'rvb@domain.tld',
                        'vzw' => 'vzw@domain.tld',
                        'webmaster' => 'webmaster@domain.tld',
                    ]),
            ]);
    }
}
