<?php

declare(strict_types=1);

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * @see https://github.com/rappasoft/laravel-boilerplate/blob/master/database/seeders/Traits/TruncateTable.php
 */
trait SupportsTableTruncateOperations
{
    /**
     * Method for truncating a single table in the database.
     *
     * @param  string $table The name of the table that u wish to truncate.
     * @return mixed
     */
    protected function truncate(string $table): mixed
    {
        return match (DB::getDriverName()) {
            'mysql' => DB::table($table)->truncate(),
            'pgsql' => DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE'),
            'sqlite', 'sqlsrv' => DB::statement('DELETE FROM ' . $table),
            default => false,
        };
    }

    /**
     * Method for truncating multiple tables in the database.
     *
     * @param  array<int, string> $tables The tables that u wish to truncate.
     * @return void
     */
    protected function truncateMultiple(array $tables): void
    {
        collect($tables)->each(function ($table): void {
            $this->truncate($table);
        });
    }
}
