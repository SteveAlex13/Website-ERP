<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankTransaction>
 */
class BankTransactionFactory extends Factory
{
    protected $model = BankTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_account_id' => BankAccount::factory(),
            'transaction_at' => now(),
            'type' => 'credit',
            'payment_method' => 'bank_transfer',
            'description' => fake()->sentence(),
            'amount' => fake()->numberBetween(10000, 1000000),
        ];
    }
}
