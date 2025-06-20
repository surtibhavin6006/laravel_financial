<?php

namespace App\Models;

use App\Policies\TransactionPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(TransactionPolicy::class)]
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'finance_transaction';
    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'transaction_date',
        'note'
    ];

    public function budget()
    {
        return $this->hasOne(
            Budget::class,
            'id',
            'budget_id'
        );
    }

    public function income()
    {
        return $this->hasOne(
            IncomeCategory::class,
            'id',
            'income_category_id'
        );
    }


}
