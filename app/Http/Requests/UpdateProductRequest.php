<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|min:3|max:12|unique:products,title',
            'price' => 'nullable|numeric|min:0|max:200',
            'eID' => 'nullable|integer',
            'categories_id' => 'nullable|array', 
            'categories_id.*' => 'exists:categories,id', 
        ];
    }
}
