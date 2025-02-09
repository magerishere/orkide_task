<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Bank\Database\Factories\BankAccountFactory;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $bankRepo = app(BankRepositoryInterface::class);
            $bankAccountRepo = app(BankAccountRepositoryInterface::class);

            $data = $bankAccountRepo->mergeCreateData(data: [
                'user_mobile' => $user->mobile,
                'status' => null,
                'type' => null,
            ], removeIfNull: true);

            for ($i = 0; $i < rand(1, 5); $i++) {
                $bank = $bankRepo->freshQuery()->randomly()->first()->getModel();
                $data['bank_code'] = $bank->code;
                $bankAccountRepo->getModel()::factory()->create(attributes: $data);
            }
        });
    }

}
