<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'categoria' => 'required|string|max:50|unique:categorias,categoria',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $categoryId = $this->route('id');
            $rules['categoria'] = 'required|string|max:50|unique:categorias,categoria,' . $categoryId;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'categoria.required' => 'El nombre de la categoría es obligatorio.',
            'categoria.unique' => 'Ya existe una categoría con este nombre.'
        ];
    }
}
