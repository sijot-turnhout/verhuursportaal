<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SecurityDepositRefundReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lease:refund-deposit-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $dueDepositRefunds = $this->getDueDepositRefunds();

        $dueDepositRefunds->each(function (Deposit $deposit): void {
            dd('Implement logic');
        });
    }

    private function getDueDepositRefunds(): Collection
    {
        return Deposit::query()
            ->whereDate('refund_at', '<', now()->toDateString())
            ->where('refunded_at', '=', null)->get();
    }
}
