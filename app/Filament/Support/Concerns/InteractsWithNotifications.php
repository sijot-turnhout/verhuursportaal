<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use Filament\Notifications\Notification;

trait InteractsWithNotifications
{
    public function filamentFlashErrorNotification(?string $title = null, ?string $icon = null, ?string $iconColor = null): void
    {
        $this->filamentFlashNotification($title, $icon, $iconColor)->success()->send();
    }

    protected function filamentFlashNotification(?string $title = null, ?string $icon = null, ?string $iconColor = null): void
    {
        Notification::make()->title($title)->icon($icon)->iconColor($iconColor);
    }
}
