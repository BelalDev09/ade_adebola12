<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // permission check thakle ekhane korte paro
    }

    public function rules()
    {
        return [
            'vendor_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'medias.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB max per file
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
