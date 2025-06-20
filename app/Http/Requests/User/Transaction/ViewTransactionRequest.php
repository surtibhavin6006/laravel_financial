<?php

namespace App\Http\Requests\User\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class ViewTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $budget = $this->route('transaction');
        return $this->user()->can('view', $budget);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
