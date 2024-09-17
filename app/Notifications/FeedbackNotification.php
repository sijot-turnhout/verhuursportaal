<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

/**
 * FeedbackNotification class.
 *
 * This notification class represents an email notification sent to tenants
 * to request feedback on their lease.
 */
final class FeedbackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param Carbon $validUntil  The validity date until which the feedback link remains active.
     * @param Lease  $lease       The Lease instance for which feedback is being requested.
     */
    public function __construct(
        public readonly Carbon $validUntil,
        public readonly Lease $lease,
    ) {}

    public function middleware(): array
    {
        return [Skip::unless($this->lease->canSendOutFeedbackNotification())];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>   Returns an array of delivery channels. In this case, ['mail'] indicating email.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  object $notifiable  The notifiable entity receiving the notification (usually the user).
     * @return MailMessage         Returns a MailMessage instance representing the email notification.
     */

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(trans('Feedback omtrent je verhuring bij Sint-Joris Turnhout'))
            ->greeting(trans('Beste :name', ['name' => $this->lease->tenant->name]))
            ->line(trans('We werken er altijd aan om ons domein en onze diensten precies te maken wat u nodig heeft voor uw jongerenorganisatie. Uw feedback helpt ons om te beslissen welk punt we moeten verbeteren.'))
            ->line(trans('Om ons te helpen ons domein en onze services zo goed mogelijk te maken, vragen we vriendelijk om uw feedback. Het invullen van het enquêteformulier duurt slechts enkele minuten.'))
            ->action('Ja, ik wil feedback geven', $this->composeFeedbackUrl($this->validUntil));
    }

    /**
     * Compose the feedback submission URL with a temporary signed route.
     *
     * @param  Carbon $validUntil  The validity date until which the feedback link remains active.
     * @return string              Returns the URL string for the feedback submission form.
     */
    private function composeFeedbackUrl(Carbon $validUntil): string
    {
        return URL::temporarySignedRoute('feedback.submit', $validUntil, [
            'lease' => $this->lease,
        ]);
    }
}
