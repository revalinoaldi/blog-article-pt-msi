<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255|min:4',
            'categories' => 'required|exists:article_categories,id',
            'file_input' => 'image|file|mimes:png,jpg,jpeg,svg|max:2048',
            'body' => 'required||min:4',
            'is_publish' => 'required'
        ];
    }
}
