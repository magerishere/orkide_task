<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\User\Repository\Contracts\UserRepositoryInterface;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(UserRepositoryInterface $userRepository): void
    {
        foreach ($this->defaultUsers() as $user) {
            $userRepository->getModel(asResource: false)::factory()->create(attributes: $user);
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
