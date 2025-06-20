<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Transaction\CreateCreditTransactionRequest;
use App\Http\Requests\User\Transaction\CreateDebitTransactionRequest;
use App\Http\Requests\User\Transaction\DeleteTransactionRequest;
use App\Http\Requests\User\Transaction\ViewTransactionRequest;
use App\Models\Budget;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use App\Services\User\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        public readonly TransactionService $financeTransactionService
    )
    {}

    public function dashboard(Request $request): array
    {
        $dashboard = $this->financeTransactionService->getMyDashboard($request->all());
        return [
            'data' => $dashboard,
            'message' => 'Dashboard Data'
        ];
    }

    public function index(Request $request): array
    {
        $allTransactions = $this->financeTransactionService->getAllTransactions($request->all());
        return [
            'data' => $allTransactions->count() ? $allTransactions : null,
            'message' => 'Get all transactions successful'
        ];
    }

    public function createDebit(CreateDebitTransactionRequest $request, Budget $budget): array
    {
        $createdData = $this->financeTransactionService->createDebitEntryData($request, $budget);

        return [
            'message' => 'Saved',
            'data' => $createdData
        ];
    }

    public function createCredit(CreateCreditTransactionRequest $request, IncomeCategory $incomeCategory): array
    {
        $createdData = $this->financeTransactionService->createIncomeEntryData($request, $incomeCategory);

        return [
            'message' => 'Saved',
            'data' => $createdData
        ];
    }


    public function view(ViewTransactionRequest $request, Transaction $transaction): array
    {
        return [
            'message' => 'Fetched transaction successful',
            'data' => $transaction
        ];
    }

    public function delete(DeleteTransactionRequest $request, Transaction $transaction): array
    {
        $deleteId = $transaction->id;
        $this->financeTransactionService->deleteData($transaction);
        return [
            'message' => 'deleted transaction successful',
            'data' => [
                'id' => $deleteId
            ]
        ];
    }

}
