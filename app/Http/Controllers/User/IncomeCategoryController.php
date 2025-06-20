<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IncomeCategory\CreateIncomeCategoryRequest;
use App\Http\Requests\User\IncomeCategory\UpdateIncomeCategoryRequest;
use App\Models\IncomeCategory;
use App\Services\User\IncomeCategoryService;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function __construct(
        public readonly IncomeCategoryService $incomeCategoryService
    )
    {}

    public function index(Request $request): array
    {
        $data = $this->incomeCategoryService->getAll($request->user()->id);

        return [
            'message' => 'List of Income Categories',
            'data' => $data,
        ];
    }

    public function create(CreateIncomeCategoryRequest $request): array
    {
        $data = $this->incomeCategoryService->create($request->user()->id,$request->all());

        return [
            'message' => 'List of Income Categories',
            'data' => $data,
        ];
    }

    public function update(UpdateIncomeCategoryRequest $request, IncomeCategory $incomeCategory): array
    {
        $data = $this->incomeCategoryService->update($incomeCategory,$request->all());

        return [
            'message' => 'Updated Income Category',
            'data' => $data,
        ];
    }
}
