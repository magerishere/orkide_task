<?php

namespace Modules\Bank\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;

class BankDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(BankRepositoryInterface $bankRepository): void
    {
        foreach ($this->defaultBanks() as $data) {
            $data = $bankRepository->mergeCreateData(data: $data);
            $bankRepository->getModel()::factory()->create(attributes: $data);
        }
    }

    private function defaultBanks(): array
    {
        return [
            [
                'prefix_card_number' => '627381',
                'name' => 'Ansar',
                'name_fa' => 'انصار',
            ],
            [
                'prefix_card_number' => '502229',
                'name' => 'Pasargad',
                'name_fa' => 'پاسارگاد',
            ],
            [
                'prefix_card_number' => '505785',
                'name' => 'Iran Zamin',
                'name_fa' => 'ایران زمین',
            ],
            [
                'prefix_card_number' => '502806',
                'name' => 'Shahr',
                'name_fa' => 'شهر',
            ],
            [
                'prefix_card_number' => '622106',
                'name' => 'Parsian',
                'name_fa' => 'پارسیان',
            ],
            [
                'prefix_card_number' => '502908',
                'name' => "Tose'e ta'avon",
                'name_fa' => 'توسعه تعاون',
            ],
            [
                'prefix_card_number' => '639194',
                'name' => 'Parsian',
                'name_fa' => 'پارسیان',
            ],
        ];
    }
}
