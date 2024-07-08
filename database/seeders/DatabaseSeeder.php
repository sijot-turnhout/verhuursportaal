<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Concerns\SupportsForeignKeyConfigurationOperations;
use Database\Seeders\Concerns\SupportsTableTruncateOperations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use SupportsForeignKeyConfigurationOperations;
    use SupportsTableTruncateOperations;
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->disableForeignKeys();

        $this->handleAbortIfNotInCorrectEnvironment();
        $this->truncateMultiple(['utilities', 'users', 'tenants', 'leases', 'locals']);

        // Start seeding the data with actual data for demo purposes.
        $this->call(UserTableSeeder::class);
        $this->call(LeaseTableSeeder::class);
        $this->call(LocalTableSeeder::class);

        $this->enableForeignKeys();
    }

    private function handleAbortIfNotInCorrectEnvironment(): void
    {
        if (app()->environment(['prod', 'production', 'staging'])) {
            $this->command->error('It seems that the application is running in an production or staging environment.');
            $this->command->error("There for we can't handle the database seeding because its strictly meant for development of testing environments.");

            return;
        }
    }
}
