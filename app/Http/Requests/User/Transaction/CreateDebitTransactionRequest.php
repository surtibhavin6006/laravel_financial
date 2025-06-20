<?php

namespace App\Http\Requests\User\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CreateDebitTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $budget = $this->route('budget');
        return $this->user()->can('create', $budget);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'transaction_date' => 'required|date|date_format:Y-m-d',
        ];
    }
}
