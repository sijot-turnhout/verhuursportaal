<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\UserGroup;
use App\Filament\Clusters\Billing\Resources\DepositResource\Pages\ListDeposits;
use App\Models\Deposit;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Contracts\Database\Eloquent\Builder;

use function Termwind\render;

/**
 * Class SecurityDepositRefundReminder
 *
 * This command checks for due security deposit refunds and notifies authorized administrators.
 *
 * It queries the database for deposits that are due for refund and have not been refunded yet,
 * it marks them as due for refund, and sends a notfication to administrators so that they can look after it
 * and resolve the issue.
 *
 * @todo Document this console command in with a clasdoc en docblocks.
 * @todo Integrate this console command in the documentation wiki.
 *
 * @package App\Console\Commands
 */
final class SecurityDepositRefundReminder extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     * Command can be executed using php artisan lease:refund-deposit-reminder
     *
     * @var string
     */
    protected $signature = 'lease:refund-deposit-reminder';

    /**
     * The console command description shown in artisan help.
     *
     * @var string
     */
    protected $description = 'Checks fdor due security deposit refunds and notifies authorized administrators to process the refunds.';

    /**
     * Indicates whether the command should be hidden from the console command list.
     *
     * @var bool
     */
    protected $hidden = true;


    /**
     * Execute the console command.
     *
     * Retrieves due deposit refunds, outputs header information, processes each due deposit,
     * marks it for refund, and sends notifications if any deposits require attention.
     *
     * @return int  Command exit code (0 for success)
     */
    public function handle(): int
    {
        $dueDepositRefunds = $this->getDueDepositRefunds();

        $this->consoleOutputHeader();

        $dueDepositRefunds->get()->each(function (Deposit $deposit): void {
            $deposit->markAsDueRefund();
            $this->consoleOutputLine($deposit);
        });

        if ($dueDepositRefunds->count() > 0) {
            $this->notifyAdministrators();
        }

        // Add an additional whitespece area to the bottom of the command output.
        // To ensure that the output is not squashed against a new input line from the CLI
        render('<span class="pb-1"></span>');

        return self::SUCCESS;
    }

    /**
     * Sends notifications to administrators about due deposit refunds.
     *
     * Retrieves users in the Vzw and Rvb user groups and sends a notification
     * indicating that some security deposits are due for refund.
     *
     * @return void
     */
    public function notifyAdministrators(): void
    {
        $users = User::query()
            ->where('user_group', UserGroup::Vzw)
            ->orWhere('user_group', UserGroup::Rvb)
            ->get();

        Notification::make()
            ->title('Terugbetaling(en) vereist van waarborgen')
            ->icon('heroicon-o-exclamation-circle')
            ->body('De terugbetalingstermijn van sommige huurwaarborgen is verstreken. Gelieve dit na te kijken.')
            ->danger()
            ->actions([
                Action::make('view-expired')
                    ->label('Bekijk waarborgen')
                    ->translateLabel()
                    ->url(ListDeposits::getUrl(parameters: ['activeTab' => 4]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($users);
    }

    /**
     * Retrieves deposits that are due for refund.
     *
     * Queries deposit where:
     * - Refund date has passed (refund < current date)
     * - Not yet refunded (refunded_at is null)
     *
     * @return Builder  Query builder for the due deposit refunds.
     */
    private function getDueDepositRefunds(): Builder
    {
        return Deposit::query()
            ->whereDate('refund_at', '<', now()->toDateString())
            ->where('refunded_at', '=', null);
    }

    /**
     * Outputs a formatted line for a signle deposit to the console.
     *
     * Uses a blade template toà fromat the output with:
     * - Lease reference number
     * - Paid amount
     *
     * @param  Deposit $deposit  The deposit to output the informa^tion for.
     * @return void
     */
    private function consoleOutputLine(Deposit $deposit): void
    {
        $view = view('console.dotted-line-output', [
            'reference' => $deposit->lease->reference_number,
            'paid' => $deposit->paid_amount,
        ]);

        // Honestly i don't know why it throws an error when i don't use ->render()
        // On the $view variable. Maybe i should invest time to debugging this behaviour.
        render($view->render());
    }

    /**
     * Outputs the header information to the console.
     *
     * Displays:
     * - Title with count of the affected rows
     * - Using a Blade template for consistent formatting
     *
     * @return void
     */
    private function consoleOutputHeader(): void
    {
        $view = view('console.information-header', [
            'title' => trans('Security deposits status changes (:affected deposits affected)', [
                'affected' => $this->getDueDepositRefunds()->count(),
            ]),
        ]);

        // Honestly i don't know why it throws an error when i don't use ->render()
        // On the $view variable. Maybe i shouuld invest time to debugging this behaviour.
        render($view->render());
    }
}
