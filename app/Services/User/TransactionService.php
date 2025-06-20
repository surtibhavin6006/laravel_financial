<?php

namespace App\Services\User;

use App\Models\Budget;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

readonly class TransactionService
{
    public function __construct(
        private Transaction $financeTransaction
    )
    {}

    public function createDebitEntryData(Request $financeTrans, Budget $budget): Transaction
    {
        $financeTransaction = (new $this->financeTransaction);
        $financeTransaction->budget_id = $budget->id;

        return $this->createTransaction($financeTransaction,$financeTrans);
    }

    public function createIncomeEntryData(Request $financeTrans, IncomeCategory $budget): Transaction
    {
        $financeTransaction = (new $this->financeTransaction);
        $financeTransaction->income_category_id = $budget->id;

        return $this->createTransaction($financeTransaction,$financeTrans);
    }

    public function createTransaction(Transaction $financeTransaction,Request $financeTrans): Transaction
    {
        $financeTransaction->user_id = $financeTrans->user()->id;
        $financeTransaction->amount = $financeTrans['amount'];
        $financeTransaction->description = $financeTrans['description'];
        $financeTransaction->note = $financeTrans['note'] ?? '';
        $financeTransaction->transaction_date = $financeTrans['transaction_date'] ?? '';
        $financeTransaction->save();

        return $this->loadIncomeAndBudget($financeTransaction);
    }

    public function getAllTransactions($params = []): Collection
    {
        $query = (new $this->financeTransaction);
        if(isset($params['user_id'])){
            $query = $query->where('user_id', $params['user_id']);
        }
        $query = $query->orderBy('transaction_date', 'desc');
        $query = $query->with(['budget', 'income']);
        $data = $query->get();
        return $this->convertToDateWiseTransactions($data);
    }

    private function convertToDateWiseTransactions($transactions): Collection
    {
        return $transactions->groupBy(function ($item) {
            return Carbon::parse($item->transaction_date)->format('Y-m-d');
        });
    }

    public function deleteData(Transaction $transaction): ?bool
    {
        return $transaction->delete();
    }

    public function loadIncomeAndBudget(Transaction $transaction): Transaction
    {
        return $transaction->load(['budget','income']);
    }

    public function getMyDashboard($userId)
    {
        return [
            'totalIncome' => $this->totalIncome($userId),
            'totalIncomeOverview' => $this->totalIncomeOverview($userId),
            'totalExpense' => $this->totalExpense($userId),
            'totalExpenseOverview' => $this->totalExpenseOverview($userId),
        ];
    }

    private function currentBalance($userId)
    {
        $transaction = (new $this->financeTransaction);
        return $transaction->where('user_id',$userId)->sum('amount');
    }

    private function totalIncome($userId)
    {
        $transaction = (new $this->financeTransaction);
        return $transaction->where('user_id',$userId)
            ->whereHas('income')
            ->sum('amount');
    }

    private function totalIncomeOverview($userId)
    {
        $transaction = (new $this->financeTransaction);
        return $transaction->where('user_id',$userId)
            ->select('income_category_id', DB::raw('SUM(amount) as amount'))
            ->whereHas('income')
            ->with('income')
            ->groupBy('income_category_id')
            ->get();
    }

    private function totalExpense($userId)
    {
        $transaction = (new $this->financeTransaction);
        return $transaction->where('user_id',$userId)
            ->whereHas('budget')
            ->sum('amount');
    }

    private function totalExpenseOverview($userId)
    {
        $transaction = (new $this->financeTransaction);
        return $transaction->where('user_id',$userId)
            ->select('budget_id', DB::raw('SUM(amount) as amount'))
            ->whereHas('budget')
            ->groupBy('budget_id')
            ->with('budget')
            ->get();
    }
}
