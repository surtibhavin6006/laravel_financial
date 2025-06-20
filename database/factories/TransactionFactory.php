<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\IncomeCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinanceTransaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'budget_id' => null,
            'income_category_id' => null,
            'amount' => $this->faker->randomFloat(2, -100, 100),
            'description' => $this->faker->sentence(),
            'note' => $this->faker->sentence(),
            'transaction_date' => Carbon::now()->subDays(rand(0, 90)),
        ];
    }

    public function debit(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'budget_id' => Budget::factory(),
        ]);
    }

    public function credit(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomFloat(2, -100, -1),
            'income_category_id' => IncomeCategory::factory(),
        ]);
    }
}
