<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|integer|min:1', // Precio en centavos
            'categoria_id' => 'required|exists:categorias,id',
            'slug' => 'required|string|unique:productos,slug',
        ];

        if($this->isMethod('put') || $this->isMethod('patch')){
          $productoId = $this->route('id');
          $rules['slug'] = 'required|string|unique:productos, slug' . $productoId;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
          'nombre.required' => 'El nombre del producto es obligatorio.',
          'stock.integer' => 'El stock debe ser un número entero.',
          'precio.integer' => 'El precio debe ser un número entero (centavos).',
          'categoria_id.exists' => 'La categoría seleccionada no existe.',
          'slug.unique' => 'Este slug ya está en uso por otro producto.',
        ];
    }
}
