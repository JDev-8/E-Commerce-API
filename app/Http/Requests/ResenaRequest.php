<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResenaRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'producto_id' => 'required|exists:productos,id',
      'puntuacion'  => 'required|integer|min:1|max:5',
      'comentario'  => 'nullable|string|max:1000',
    ];
  }

  public function messages()
  {
    return [
      'producto_id.required' => 'Debes especificar el producto.',
      'producto_id.exists'   => 'El producto no existe.',
      'puntuacion.required'  => 'Debes dar una puntuación.',
      'puntuacion.min'      => 'La puntuación mínima es 1.',
      'puntuacion.max'      => 'La puntuación máxima es 5.',
      'comentario.max'      => 'El comentario no puede exceder los 1000 caracteres.',
    ];
  }
}
