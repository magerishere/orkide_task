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
        foreach ($this->defaultUsers() as $data) {
            $data = $userRepository->mergeCreateData(data: $data);
            $userRepository->getModel()::factory()->create(attributes: $data);
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
            [
                'mobile' => '09100000004',
            ],
            [
                'mobile' => '09100000005',
            ],
        ];
    }
}
