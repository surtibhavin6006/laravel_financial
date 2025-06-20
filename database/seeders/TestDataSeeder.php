<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {

            $budgets = Budget::factory(5)->create([
                'user_id' => $user->id,
            ]);

            $budgets->each(function ($budget) use ($user) {
                Transaction::factory(20)
                    ->debit()
                    ->state([
                        'user_id' => $user->id,
                        'budget_id' => $budget->id,
                    ])
                    ->create();
            });

            $income = IncomeCategory::factory(5)->create([
                'user_id' => $user->id,
            ]);

            $income->each(function ($inc) use ($user) {
                Transaction::factory(20)
                    ->credit()
                    ->state([
                        'user_id' => $user->id,
                        'income_category_id' => $inc->id,
                    ])
                    ->create();
            });

        });
    }
}
