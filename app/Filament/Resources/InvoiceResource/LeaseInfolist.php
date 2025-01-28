<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource;

use App\Enums\LeaseStatus;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Filament\Clusters\Billing\Resources\DepositResource\Pages\ViewDeposit;
use App\Models\Lease;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
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
        return $infolist->schema([
            Tabs::make('lmease-information-tabs')
                ->columnSpan(12)
                ->tabs([
                    self::tenantInformationSection(),
                    self::leaseInformationSection(),
                    self::securityDepositInformationSection(),
                    self::feedbackInformationSection(),
                    self::cancellationInformationTab(),
                ]),
        ]);
    }

    /**
     * Create the cancellation information section.
     *
     * @return Tab
     */
    private static function cancellationInformationTab(): Tab
    {
        return Tab::make('Annulatie gegevens')
            ->translateLabel()
            ->icon(fn(Lease $lease): string => $lease->status->getIcon())
            ->visible(fn (Lease $lease): bool => $lease->status->is(LeaseStatus::Cancelled))
            ->columns(12)
            ->schema([
                TextEntry::make('cancelled_at')
                    ->label('Geannuleerd op')
                    ->translateLabel()
                    ->color('primary')
                    ->date()
                    ->columnSpan(3),
                TextEntry::make('cancellation_reason')
                    ->label('Reden van de annulatie')
                    ->translateLabel()
                    ->columnSpan(9),
            ]);
    }

    /**
     * Create the tenant information section.
     *
     * @return Tab The tenant information section.
     */
    private static function tenantInformationSection(): Tab
    {
        return Tab::make('Huurder')
            ->icon('heroicon-o-user-circle')
            ->columns(12)
            ->schema([
                TextEntry::make('tenant.name')->label(trans('Naam'))->icon('heroicon-o-user-circle')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.email')->label(trans('Email'))->icon('heroicon-o-envelope')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.phone_number')->label(trans('Telefoon nummer'))->icon('heroicon-o-phone')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.created_at')->label(trans('Geregistreerd op'))->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
                TextEntry::make('tenant.address')->label(trans('Adres'))->columnSpan(6)->icon('heroicon-o-home')->iconColor('primary'),

                TextEntry::make('risk_accessment_label')
                    ->label('Risico profiel')
                    ->translateLabel()->columnSpan(3)
                    ->badge()
                    ->hintAction(fn(Action $action) => $action->make('check-documentation')
                        ->label('uitleg')
                        ->url('https://sijot-turnhout.github.io/verhuur-portaal-documentatie/leases/incidents.html#risico-analyse')
                        ->openUrlInNewTab()
                        ->color('primary')
                        ->icon('heroicon-m-question-mark-circle')),

                TextEntry::make('tenant.banned_at')->label(trans('Op de zwarte lijst sinds'))->default('-')->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3),
            ]);
    }

    /**
     * Create the security deposit information section
     *
     * @return Tab The security deposit information section.
     */
    private static function securityDepositInformatioNSection(): Tab
    {
        return Tab::make(trans('Waarborg'))
            ->columns(12)
            ->badge(fn(Lease $lease) => $lease->depositRepaymentIsDue() ? trans('verlopen') : null)
            ->badgeColor('danger')
            ->badgeIcon('heroicon-m-bell-alert')
            ->icon('heroicon-o-credit-card')
            ->visible(fn(Lease $lease): bool => $lease->deposit()->exists())
            ->schema([
                TextEntry::make('deposit.status')
                    ->label('Waarborg status')
                    ->badge()
                    ->columnSpan(3)
                    ->translateLabel(),

                TextEntry::make('deposit.paid_amount')
                    ->label('Gestorte waarborg')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->is(DepositStatus::Paid))
                    ->money('EUR')
                    ->columnSpan(3)
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary'),

                TextEntry::make('deposit.refunded_amount')
                    ->label('Teruggestorte borg')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->isNot(DepositStatus::Paid))
                    ->money('EUR')
                    ->columnSpan(3)
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary'),

                TextEntry::make('deposit.paid_at')
                    ->label('Betaald op')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->is(DepositStatus::Paid))
                    ->date()
                    ->columnSpan(3),

                TextEntry::make('deposit.revoked_amount')
                    ->label('Ingehouden borg')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->isNot(DepositStatus::Paid))
                    ->money('EUR')
                    ->columnSpan(3)
                    ->weight(FontWeight::ExtraBold)
                    ->color('danger'),

                TextEntry::make('deposit.refunded_at')
                    ->label('Teruggestort op')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->isNot(DepositStatus::Paid))
                    ->columnSpan(3)
                    ->date(),

                TextEntry::make('deposit.refund_at')
                    ->label('Uiterste terugbetalingsdatum')
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->is(DepositStatus::Paid))
                    ->date()
                    ->columnSpan(3),

                TextEntry::make('deposit.note')
                    ->columnSpan(12)
                    ->visible(fn(Lease $lease): bool => $lease->deposit->status->notIn([DepositStatus::Paid, DepositStatus::FullyRefunded]))
                    ->label('Reden tot gedeeltelijke terugbetaling of intrekking van de waarborg')
                    ->color('gray'),

                Actions::make([
                    Action::make('Waarborg registratie beheren')
                        ->color('gray')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->iconSize(IconSize::Small)
                        ->size(ActionSize::ExtraSmall)
                        ->url(fn(Lease $lease): string => ViewDeposit::getUrl(['record' => $lease->deposit]))
                        ->openUrlInNewTab(),
                ])->columnSpan(12),
            ]);
    }

    /**
     * Create the feedback information section.
     *
     * @return Tab The feedback information section.
     */
    private static function feedbackInformationSection(): Tab
    {
        return Tab::make(trans('Feedback'))
            ->columns(12)
            ->icon('heroicon-o-chat-bubble-left-right')
            ->visible(fn(Lease $lease) => $lease->feedback()->exists())
            ->schema([
                TextEntry::make('feedback.subject')->label(trans('Onderwerp'))->icon('heroicon-o-hashtag')->iconColor('primary')->columnSpan(9),
                TextEntry::make('feedback.created_at')->date()->label(trans('Ingezonden op'))->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(3),
                TextEntry::make('feedback.message')->label(trans('Ingezonden feedback'))->columnSpan(12),
            ]);
    }

    /**
     * Create the lease information section.
     *
     * @return Tab The lease information Tab.
     */
    private static function leaseInformationSection(): Tab
    {
        return Tab::make(trans('Reservatie'))
            ->icon('heroicon-o-home-modern')
            ->columns(12)
            ->schema([
                TextEntry::make('reference_number')->label('Referentie')->columnSpan(3)->placeholder('-'),
                TextEntry::make('supervisor.name')
                    ->label(trans('Opgevold door'))
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->placeholder(trans('Geen opvolger toegewezen'))
                    ->columnSpan(3),
                TextEntry::make('persons')->label('Aantal personen')->icon('heroicon-o-users')->iconColor('primary')->columnSpan(3),
                TextEntry::make('status')->label('Verhurings status')->badge()->columnSpan(3),
                TextEntry::make('locals.name')->badge()->columnSpan(6)->label('Inbegrepen lokalen')->icon('heroicon-o-home')->default('geen lokalen gekoppeld')->iconColor('primary'),
                TextEntry::make('arrival_date')->label(trans('aankomst datum'))->date()->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
                TextEntry::make('departure_date')->label(trans('vertrek datum'))->date()->icon('heroicon-o-calendar')->iconColor('primary')->columnSpan(3),
            ]);
    }
}
