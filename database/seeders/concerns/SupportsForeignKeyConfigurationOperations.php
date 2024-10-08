<?php

declare(strict_types=1);

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * @see https://github.com/rappasoft/laravel-boilerplate/blob/master/database/seeders/Traits/DisableForeignKeys.php
 */
trait SupportsForeignKeyConfigurationOperations
{
    /**
     * Array of the commands to enable/disable the foreign keys in the database tables.
     *
     * @var array<string, array<string, string>>
     */
    private $commands = [
        'mysql' => [
            'enable' => 'SET FOREIGN_KEY_CHECKS=1;',
            'disable' => 'SET FOREIGN_KEY_CHECKS=0;',
        ],
        'sqlite' => [
            'enable' => 'PRAGMA foreign_keys = ON;',
            'disable' => 'PRAGMA foreign_keys = OFF;',
        ],
        'sqlsrv' => [
            'enable' => 'EXEC sp_msforeachtable @command1="print \'?\'", @command2="ALTER TABLE ? WITH CHECK CHECK CONSTRAINT all";',
            'disable' => 'EXEC sp_msforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT all";',
        ],
        'pgsql' => [
            'enable' => 'SET CONSTRAINTS ALL IMMEDIATE;',
            'disable' => 'SET CONSTRAINTS ALL DEFERRED;',
        ],
    ];

    /**
     * Disable foreign key checks for current db driver.
     *
     * @return void
     */
    protected function disableForeignKeys(): void
    {
        DB::statement($this->getDisableStatement());
    }

    /**
     * Enable foreign key checks for current db driver.
     *
     * @return void
     */
    protected function enableForeignKeys(): void
    {
        DB::statement($this->getEnableStatement());
    }

    /**
     * Return current driver enable command.
     *
     * @return mixed
     */
    private function getEnableStatement(): mixed
    {
        return $this->getDriverCommands()['enable'];
    }

    /**
     * Return current driver disable command.
     *
     * @return mixed
     */
    private function getDisableStatement(): mixed
    {
        return $this->getDriverCommands()['disable'];
    }

    /**
     * Returns command array for current db driver.
     *
     * @return mixed
     */
    private function getDriverCommands(): mixed
    {
        return $this->commands[DB::getDriverName()];
    }
}
