<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsDraftRequest extends FormRequest
{
/**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'photo' => 'nullable|mimes:jpg,png,jpeg',
            'multi_photo' => 'nullable',
            'content' => 'nullable',
            'category' => 'array|nullable',
            'category.*' => 'nullable',
            'sub_category' => 'array|nullable',
            'sub_category.*' => 'nullable',
            'tags' => 'array|nullable',
            'tags.*' => 'nullable',
            'upload_date' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'photo.required' => 'Foto tidak boleh kosong',
            'multi_photo.required' => 'Foto tidak boleh kosong',
            'content.required' => 'Konten tidak boleh kosong',
            'category.required' => 'Kategori tidak boleh kosong',
            'tags.required' => 'Tags tidak boleh kosong',
            'upload_date.required' => 'Tanggal tidak boleh kosong',
            'sub_category.required' => 'Sub kategori tidak boleh kosong',
        ];
    }
}