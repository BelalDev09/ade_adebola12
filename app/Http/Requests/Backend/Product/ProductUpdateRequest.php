<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // permission check thakle ekhane korte paro
    }

    public function rules()
    {
        $productId = $this->route('id');

        return [
            'vendor_id' => 'sometimes|exists:users,id',
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255|unique:products,name,' . $productId,
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'medias.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
