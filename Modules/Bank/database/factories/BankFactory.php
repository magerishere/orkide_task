<?php

namespace Modules\Bank\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bank\Models\Bank;

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
            'code' => fake()->numerify('###'),
            'prefix_structure' => fake()->numerify('##'),
        ];
    }
}
