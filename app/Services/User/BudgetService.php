<?php

namespace App\Services\User;

use App\Models\Budget;

readonly class BudgetService
{
    public function __construct(
        public Budget $budget
    )
    {}

    public function getAllBudgets($userId)
    {
        $budgets = (new $this->budget);
        $budgets = $budgets->where('user_id', $userId);
        return $budgets->get();
    }

    public function createBudget($userId,array $budget): Budget
    {
        $budgets = (new $this->budget);
        $budgets->user_id = $userId;
        $budgets->name = $budget['name'];
        $budgets->amount = $budget['amount'];
        $budgets->save();

        return $budgets;
    }

    public function getBudgetById($budgetId)
    {
        $budget = (new $this->budget);
        return $budget->find($budgetId);
    }

    public function updateBudgetAmount(Budget $budget, $params): Budget
    {
        if(!empty($params)) {
            $budget->name = $params['name'];
        }
        if(!empty($params)) {
            $budget->amount = $params['amount'];
        }
        $budget->save();
        return $budget;
    }

}
