<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('ban:delete-expired')->everyMinute()->withoutOverlapping();
Schedule::command('lease:refund-deposit-reminder')->dailyAt('00:01');
