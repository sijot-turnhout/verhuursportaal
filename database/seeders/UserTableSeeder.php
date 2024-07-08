<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserGroup::cases() as $group) {
            User::factory()->create(['email' => mb_strtolower($group->name) . '@domain.tld', 'user_group' => $group->value]);
        }
    }
}
