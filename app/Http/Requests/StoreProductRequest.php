<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title' => 'required|string|min:3|max:12|unique:products,title',
            'price' => 'required|numeric|min:0|max:200',
            'eID' => 'required|integer',
            'categories_id' => 'required|array', 
            'categories_id.*' => 'exists:categories,id', 
        ];
    }
}
