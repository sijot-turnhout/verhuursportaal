<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Traits;

use App\Filament\Resources\LeaseResource\Pages\ListLeases;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;

/**
 * Trait UsesArchivingSystemActions
 *
 * This trait provides reusable methods for handling archiving-related actions in Filament tables.
 * It includes actions for soft-deleting (archiving), restoring, and force-deleting leases
 * in both individual and bulk formats. Additionally, the visibility of these actions is
 * controlled based on the active tab, ensuring that certain actions appear only in the
 * appropriate context (e.g., archive-related actions only appear in the archive tab).
 *
 * @todo The flash messages on the action functions are still incorrect. Can be fixed with the ->successNotificationTitle() function call
 * @todo The items out of the archive should also have a delete method.
 *
 * @package App\Filament\Resources\LeaseResource\Traits
 */
trait UsesArchivingSystemActions
{

}
