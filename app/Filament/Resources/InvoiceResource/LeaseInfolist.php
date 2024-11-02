<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource;

use App\Models\Lease;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconSize;

/**
 * Class LeaseInfolist
 *
 * This class defines the structure of the Lease Information List (Infolist) for displaying
 * tenant, lease, and feedback information in the InvoiceResource.
 *
 * @todo Extends Infolist scheme docblocks.
 * @todo This infolist class is possibly placed in the wrong resource. We need to investigate this and fix if it is indeed wrongly placed.
 *
 * @package App\Filament\Resources\InvoiceResource
 */
final readonly class LeaseInfolist
{
    /**
     * Create the Infolist schema.
     *
     * @param  Infolist $infolist  The infolist instance.
     * @return Infolist            The infolist with the defined schema.
     */
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                self::tenantInformationSection(),
                self::leaseInformationSection(),
                self::feedbackInformationSection(),
            ]);
    }

    /**
     * Create the tenant information section.
     *
     * @return Section The tenant information section.
     */
    private static function tenantInformationSection(): Section
    {
        return self::baseSection(name: 'Huurder informatie')
            ->description(trans('Alle benodigde informatie omtrent de huurder die de verhuring heeft aangevraagd'))
            ->icon('heroicon-o-user-circle')
            ->columns(12)
            ->schema([
                TextEntry::make('tenant.name')->label(trans('Naam'))->icon('heroicon-o-user-circle')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.email')->label(trans('Email'))->icon('heroicon-o-envelope')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.phone_number')->label(trans('Telefoon nummer'))->icon('heroicon-o-phone')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.created_at')->label(trans('Geregistreerd op'))->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.address')->label(trans('Adres'))->columnSpan(9)->icon('heroicon-o-home')->iconColor('primary'),
                TextEntry::make('tenant.banned_at')->label(trans('Op de zwarte lijst sinds'))->default('-')->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3),
            ]);
    }

    /**
     * Create the feedback information section.
     *
     * @return Section The feedback information section.
     */
    private static function feedbackInformationSection(): Section
    {
        return self::baseSection(name: trans('Feedback'))
            ->description(trans('Gebruikersfeedback die ons domein en of diensten kunnen verbeteren.'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->visible(fn(Lease $lease) => $lease->feedback()->exists())
            ->schema([
                TextEntry::make('feedback.subject')->label(trans('Onderwerp'))->icon('heroicon-o-hashtag')->iconColor('primary')->columnSpan(9),
                TextEntry::make('feedback.created_at')->date()->label(trans('Ingezonden op'))->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3),
                TextEntry::make('feedback.message')->label(trans('Ingezonden feedback'))->columnSpan(12),
            ])->columns(12);
    }

    /**
     * Create the lease information section.
     *
     * @return Section The lease information section.
     */
    private static function leaseInformationSection(): Section
    {
        return self::baseSection(name: trans('Reservatie informatie'))
            ->description(trans('Alle nodige informatie omtrent de aangevraagde verhuring'))
            ->icon('heroicon-o-home-modern')
            ->headerActions([
                /** @todo Complete this function */
                \Filament\Infolists\Components\Actions\Action::make('deze verhuring opvolgen')
                    ->size(ActionSize::ExtraSmall)
                    ->visible(false)
                    ->color('gray')
                    ->icon('heroicon-o-user-plus')
                    ->iconSize(IconSize::Small),
            ])
            ->schema([
                TextEntry::make('supervisor.name')
                    ->label(trans('Opgevold door'))
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->placeholder('- geen opvolger aangeduid')
                    ->columnSpan(3),
                TextEntry::make('persons')->label('Aantal personen')->icon('heroicon-o-users')->iconColor('primary')->columnSpan(3),
                TextEntry::make('status')->label('Verhurings status')->badge()->columnSpan(3),
                TextEntry::make('deposit.status')->label('Waarborg')->badge()->columnSpan(3)->default('niet ingesteld'),
                TextEntry::make('locals.name')->badge()->columnSpan(6)->label('Inbegrepen lokalen')->icon('heroicon-o-home')->default('geen lokalen gekoppeld')->iconColor('primary'),
                TextEntry::make('arrival_date')->label(trans('aankomst datum'))->date()->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
                TextEntry::make('departure_date')->label(trans('vertrek datum'))->date()->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
            ])->columns(12);
    }

    /**
     * Create a base section with default properties.
     *
     * @param  string $name  The name of the section.
     * @return Section       The base section with default properties.
     */
    private static function baseSection(string $name): Section
    {
        return Section::make($name)
            ->compact()
            ->collapsible()
            ->iconSize(IconSize::Medium)
            ->collapsed()
            ->iconColor('primary');
    }
}
