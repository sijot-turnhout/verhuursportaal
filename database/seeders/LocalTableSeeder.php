<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Local;
use Illuminate\Database\Seeder;

/**
 * Class LocalTableSeeder
 *
 * Method for storing the basic locals "lokalen" in the application.
 * We know that we can attach issues to the locals. But decided to not perform those actions on the seeder,
 * mainly because this seeder can also be used in the production environment of the application.
 */
final class LocalTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getInformationAboutTheUnit() as $unit) {
            Local::query()->create(['name' => $unit['name'], 'storage_location' => $unit['storage_location']]);
        }
    }

    /**
     * The method that declares the default locals in the database application.
     *
     * @return array<int, array<string, bool|string>>
     */
    private function getInformationAboutTheUnit(): array
    {
        return [
            ['name' => 'Kapoenen lokaal', 'storage_location' => false],
            ['name' => 'Welpen lokaal', 'storage_location' => false],
            ['name' => 'Jong-giver lokaal', 'storage_location' => false],
            ['name' => 'Giver lokaal', 'storage_location' => false],
            ['name' => 'Jin lokaal', 'storage_location' => false],
            ['name' => 'Leidings lokaal', 'storage_location' => false],
            ['name' => 'Sanitaire blok', 'storage_location' => false],
            ['name' => 'Keuken', 'storage_location' => false],
            ['name' => 'Vuurcirkel', 'storage_location' => false],
            ['name' => 'Container', 'storage_location' => true],
            ['name' => 'Houten kot', 'storage_location' => true],
            ['name' => 'Ijzeren kot', 'storage_location' => true],
        ];
    }
}
