<?php

namespace App\Http\Requests\User\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CreateCreditTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $incomeCategory = $this->route('incomeCategory');
        return $this->user()->can('create', $incomeCategory);
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
