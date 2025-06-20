<?php

namespace App\Http\Requests\User\IncomeCategory;

use App\Models\IncomeCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $incomeCategory = $this->route('incomeCategory');
        return $this->user()->can('update', $incomeCategory);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }
}
