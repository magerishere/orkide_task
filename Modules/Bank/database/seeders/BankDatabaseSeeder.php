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
        foreach ($this->defaultBanks() as $bank) {
            $bankRepository->freshQuery()->create(data: $bank);
        }
    }

    private function defaultBanks(): array
    {
        return [
            [
                'code' => '001',
                'prefix_structure' => '10',
                'prefix_card_number' => '627381',
                'name' => 'Ansar',
                'name_fa' => 'انصار',
            ],
            [
                'code' => '002',
                'prefix_structure' => '11',
                'prefix_card_number' => '502229',
                'name' => 'Pasargad',
                'name_fa' => 'پاسارگاد',
            ],
            [
                'code' => '003',
                'prefix_structure' => '12',
                'prefix_card_number' => '505785',
                'name' => 'Iran Zamin',
                'name_fa' => 'ایران زمین',
            ],
            [
                'code' => '004',
                'prefix_structure' => '13',
                'prefix_card_number' => '502806',
                'name' => 'Shahr',
                'name_fa' => 'شهر',
            ],
            [
                'code' => '005',
                'prefix_structure' => '14',
                'prefix_card_number' => '622106',
                'name' => 'Parsian',
                'name_fa' => 'پارسیان',
            ],
            [
                'code' => '006',
                'prefix_structure' => '15',
                'prefix_card_number' => '502908',
                'name' => "Tose'e ta'avon",
                'name_fa' => 'توسعه تعاون',
            ],
            [
                'code' => '007',
                'prefix_structure' => '14',
                'prefix_card_number' => '639194',
                'name' => 'Parsian',
                'name_fa' => 'پارسیان',
            ],
        ];
    }
}
