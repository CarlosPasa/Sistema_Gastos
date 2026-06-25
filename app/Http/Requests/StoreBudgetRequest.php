<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $budgetId = $this->route('budget')?->id;

        return [
            'category_id' => [
                'required',
                'exists:categories,id',
                Rule::unique('budgets')
                    ->where('user_id', auth()->id())
                    ->where('month', $this->month)
                    ->where('year', $this->year)
                    ->ignore($budgetId),
            ],
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.unique' => 'Ya existe un presupuesto para esta categoría en el mes y año seleccionados.',
            'category_id.required' => 'Selecciona una categoría.',
            'amount.required' => 'Ingresa el monto del presupuesto.',
            'amount.numeric' => 'El monto debe ser un número válido.',
            'month.required' => 'Selecciona el mes.',
            'year.required' => 'Ingresa el año.',
        ];
    }
}
