<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->double('amount');
            $table->string('description');
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->nullOnDelete();
            $table->foreignId('income_category_id')->nullable()->constrained('income_categories')->nullOnDelete();
            $table->string('note')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transaction');
    }
};
