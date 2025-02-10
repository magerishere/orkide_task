<?php

namespace Modules\Bank\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bank\Models\Bank;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modules\Bank\Models\Bank>
 */
class BankFactory extends Factory
{
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->uniqueCode(),
            'prefix_structure' => $this->uniquePrefixStructure(),
        ];
    }

    private function uniquePrefixStructure(): int
    {
        $prefixStructure = randomDigits(length: 2);
        if (app(BankRepositoryInterface::class)->freshQuery()->where('prefix_structure', $prefixStructure)->exists()) {
            return $this->uniquePrefixStructure();
        }

        return $prefixStructure;
    }

    private function uniqueCode(): int
    {
        $code = randomDigits(length: 3);
        if (app(BankRepositoryInterface::class)->freshQuery()->where('code', $code)->exists()) {
            return $this->uniquePrefixStructure();
        }

        return $code;
    }
}
