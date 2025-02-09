<?php

namespace Modules\Bank\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;
use Modules\Bank\Models\BankAccount;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modules\Bank\Models\Bank>
 */
class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->numerify('##################'),
            'status' => fake()->randomElement(BankAccountStatus::cases()),
            'type' => fake()->randomElement(BankAccountType::cases()),
            'balance' => fake()->numberBetween(0, 1000000000),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (BankAccount $bankAccount) {
            $bankAccountCardRepo = app(BankAccountCardRepositoryInterface::class);

            $data = $bankAccountCardRepo->mergeCreateData(data: [
                'status' => null,
            ], removeIfNull: true);

            $bankAccountCardRepo->getModel()::factory()->forBankAccount($bankAccount)->count(rand(1, 3))->create(attributes: $data);
        });
    }
}
