<?php

namespace App\Services\User;

use App\Models\Budget;
use App\Models\IncomeCategory;

readonly class IncomeCategoryService
{
    public function __construct(
        public IncomeCategory $incomeCategory
    )
    {}

    public function getAll($userId)
    {
        $incomeCategory = (new $this->incomeCategory);
        $incomeCategory = $incomeCategory->where('user_id', $userId);
        return $incomeCategory->get();
    }

    public function create($userId, $params)
    {
        $incomeCategory = (new $this->incomeCategory);
        $incomeCategory->user_id = $userId;
        $incomeCategory->name = $params['name'];
        $incomeCategory->save();

        return $incomeCategory;
    }

    public function update(IncomeCategory $incomeCategory,$params = []): IncomeCategory
    {
        if(!empty($params['name'])){
            $incomeCategory->name = $params['name'];
        }
        $incomeCategory->save();

        return $incomeCategory;
    }
}
