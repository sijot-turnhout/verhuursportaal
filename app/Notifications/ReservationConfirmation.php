<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ReservationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Lease $lease,
    ) {}

    /** @return array<int, string> */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Tenant $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Bevestiging aanvraag tot verhuring')
            ->view('mail.reservation-confirmation', data: [
                'tenantInformation' => $notifiable,
                'leaseInformation' => $this->lease,
            ]);
    }
}
