<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|unique:usuarios,cedula',
            'nombre_usuario' => 'required|string|unique:usuarios,nombre_usuario',
            'correo_electronico' => 'required|string|email|unique:usuarios,correo_electronico',
            'telefono' => 'required|string|unique:usuarios,telefono',
            'contrasenia' => 'required|string|min:8',
        ];
    }

    public function messages(): array{
        return [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.string'   => 'Los nombres deben ser una cadena de texto válida.',
            'nombres.max'      => 'Los nombres no pueden exceder los 255 caracteres.',
            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'apellidos.string'   => 'Los apellidos deben ser una cadena de texto válida.',
            'apellidos.max'      => 'Los apellidos no pueden exceder los 255 caracteres.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.unique'   => 'Esta cédula ya se encuentra registrada en el sistema.',
            'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.unique'   => 'Este nombre de usuario ya está en uso, por favor elige otro.',
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email'    => 'Debes ingresar una dirección de correo válida.',
            'correo_electronico.unique'   => 'Este correo electrónico ya está registrado.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.unique'   => 'Este número de teléfono ya está registrado.',
            'contrasenia.required' => 'La contraseña es obligatoria.',
            'contrasenia.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
