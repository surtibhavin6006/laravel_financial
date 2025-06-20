<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Budget\CreateBudgetRequest;
use App\Http\Requests\User\Budget\UpdateBudgetRequest;
use App\Http\Requests\User\Budget\ViewBudgetRequest;
use App\Models\Budget;
use App\Services\User\BudgetService;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function __construct(
        public readonly BudgetService $budgetService
    )
    {}

    public function index(Request $request): array
    {
         return  [
             'data' => $this->budgetService->getAllBudgets($request->user()->id),
             'message' => 'All Budgets',
         ];
    }

    public function view(ViewBudgetRequest $request,Budget $budget): array
    {
        return  [
            'data' => $budget,
            'message' => 'Budget',
        ];
    }

    public function create(CreateBudgetRequest $request): array
    {
        return  [
            'data' => $this->budgetService->createBudget($request->user()->id, $request->all(['amount','name'])),
            'message' => 'Budget',
        ];
    }

    public function update(UpdateBudgetRequest $request,Budget $budget): array
    {
        $updatedData = $this->budgetService->updateBudgetAmount(
            $budget,
            $request->all(['amount','name'])
        );

        return [
            'message' => 'Budget Updated',
            'data' => $updatedData,
        ];
    }
}
