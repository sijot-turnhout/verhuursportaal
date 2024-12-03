<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Filament\Clusters\Billing\Resources\DepositResource\Pages;
use App\Filament\Clusters\Billing\Resources\DepositResource\Schemas\DepositInfolist;
use App\Filament\Clusters\Billing\Resources\DepositResource\Widgets\DepositStatsOverview;
use App\Models\Deposit;
use Filament\Clusters\Cluster;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

/**
 * Resource class for managing security deposits within the Billing cluster of the Filament admin panel.
 *
 * This class provides a comprehensive interface for administrators to manage deposit records related to lease agreements.
 * It leverages Filament's powerful resource management features, offering functionalities such as viewing, sorting, searching, and displaying detailed deposit information.
 *
 * Key features of this resource include:
 * - Displaying a table of deposits with relevant columns like lease reference number, tenant name, status, paid amount, payment date, and refund date.
 * - Providing detailed views of individual deposit records with related lease and payment information.
 * - Displaying a navigation badge indicating the number of paid deposits for quick access and overview.
 * - Incorporating widgets to visualize deposit statistics and provide valuable insights.
 * - Utilizing infolists to present comprehensive details about a selected deposit and its associated lease.
 *
 * This resource aims to streamline deposit management within the application, providing a user-friendly experience for administrators while ensuring data accuracy and consistency.
 *
 * @package App\Filament\Clusters\Billing\Resources
 */
final class DepositResource extends Resource
{
    /**
     * The eloquent model associated with this resource.
     * This defines the database table and model used for managing deposit records.
     *
     * @var string|null
     */
    protected static ?string $model = Deposit::class;

    /**
     * The navigation icon for this resource in the Filament admin panel sidebar.
     * Uses a Heroicon to visually represent the deposit resource?
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    /**
     * The singular label for this resource displayed in the Filament admin panel.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'waarborg';

    /**
     * The plural label for this resource displayed in the Filament admin panel.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Waarborgen';

    /**
     * The parent billing cluster that this resource belongs to.
     * This organizes related billing resources within the Filament admin panel.
     *
     * @var class-string<Cluster> | null
     */
    protected static ?string $cluster = Billing::class;

    /**
     * Defines the structure and content of the infolist displayed when viewing a deposit record.
     *
     * The infolist presents a detailed overview of the deposit and its associated lease, providing comprehensive information to administrators.
     * It utilizes the `DepositInfolist` schema to structure and display the relevant data.
     *
     * @param  Infolist $infolist The Filament infolist instance being configured
     * @return Infolist           The configured infolist instance.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(
            components: [DepositInfolist::getLeaseInfoSection(), DepositInfolist::getDepositInfoSection()],
        );
    }

    /**
     * Configures the table used to display a list of deposit records in the Filament admin panel.
     * This method defines the columns displayed in the table, the actions available for each record, and the content presented when no records are found.
     *
     * @param  Table $table  The Filament table instance being configured
     * @return Table         The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Geen geregistreerde waarborgen')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateDescription('Het lijkt erop dat er voor de moment geen geregistreerde waarborgen zijn die voldoen aan de opgegeven criteria')
            ->columns([
                TextColumn::make('lease.reference_number')
                    ->label('Verhuring')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-home-modern')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('lease.tenant.name')
                    ->label('Betaald door')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('Borgsom')
                    ->translateLabel()
                    ->money('EUR'),

                TextColumn::make('paid_at')
                    ->label('Betaald op')
                    ->sortable()
                    ->translateLabel()
                    ->date(),

                TextColumn::make('refund_at')
                    ->label('Terugbetalingsdatum')
                    ->sortable()
                    ->searchable()
                    ->date(),
            ])
            ->actions([Tables\Actions\ViewAction::make()]);
    }

    /**
     * Retrieves the number of paid deposits to display as a badge in the navigation menu.
     * This provides a quick overview of the number of paid deposits for administrators.
     *
     * @return string|null  The number of paid deposits as a string, or null if no deposits are paid.
     */
    public static function getNavigationBadge(): ?string
    {
        if ($count = Deposit::query()->where('status', DepositStatus::Paid)->count()) {
            return (string) $count;
        }

        return null;
    }

    /**
     * Defines the widgets to display on the deposit resources's index page.
     * These widgets can provide visualizations and summaries of deposit-related data.
     *
     * @return array<mixed> An array of widget class names to be displayed.
     */
    public static function getWidgets(): array
    {
        return [DepositStatsOverview::class];
    }

    /**
     * Defines the routes and corresponding page classes for this resource.
     * This method maps URL routes to specific page classes within the Filament admin panel, defining how different aspects of the resource are accessed and managed.
     *
     * @return array<string, PageRegistration>  An array mapping route names to their corresponding page classes and routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'view' => Pages\ViewDeposit::route('/{record}'),
        ];
    }
}
