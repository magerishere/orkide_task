<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->defaultUsers() as $user) {
            User::factory()->create(attributes: $user);
        }
    }

    private function defaultUsers(): array
    {
        return [
            [
                'mobile' => '09100000000',
            ],
            [
                'mobile' => '09100000001',
            ],
            [
                'mobile' => '09100000002',
            ],
            [
                'mobile' => '09100000003',
            ],
        ];
    }
}
