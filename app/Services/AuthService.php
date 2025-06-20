<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\IncomeCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

readonly class AuthService
{
    public function __construct(private User $user)
    {}

    public function signUp($regData): void
    {
        $user = (new $this->user);
        $user->name = $regData['name'];
        $user->email = $regData['email'];
        $user->password = Hash::make($regData['password']);
        $user->save();

        $this->createIncomeCategory($user);
        $this->createBudgetCategory($user);
    }

    private function createIncomeCategory(User $user): void
    {
        $budget = new Budget(['name' => 'Others','amount' => 0]);
        $user->budgets()->save($budget);
    }

    private function createBudgetCategory(User $user): void
    {
        $user->incomeCategories()->saveMany([
            new IncomeCategory(['name' => 'Salary']),
            new IncomeCategory(['name' => 'Bonus']),
            new IncomeCategory(['name' => 'Interest']),
        ]);
    }
}
