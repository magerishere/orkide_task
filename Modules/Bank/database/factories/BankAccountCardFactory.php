<?php

namespace Modules\Bank\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bank\Enums\BankAccountCardStatus;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Enums\BankAccountType;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankAccount;
use Modules\Bank\Models\BankAccountCard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modules\Bank\Models\Bank>
 */
class BankAccountCardFactory extends Factory
{
    protected $model = BankAccountCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(BankAccountCardStatus::cases()),
        ];
    }

    public function forBankAccount(BankAccount $bankAccount): self
    {
        return $this->state(fn(array $attributes) => [
            'bank_account_number' => $bankAccount->number,
            'number' => $bankAccount->bank->prefix_card_number . fake()->numerify('##########'),
        ]);
    }
}
