<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_registrarse()
    {
        $response = $this->postJson('/api/registrar', [
            'nombres' => 'Test',
            'apellidos' => 'User',
            'cedula' => '111222333',
            'nombre_usuario' => 'testuser',
            'correo_electronico' => 'test@example.com',
            'telefono' => '099111222',
            'contrasenia' => 'password123',
            'is_admin' => false
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'access_token',
            'usuario'
        ]);

        $this->assertDatabaseHas('usuarios', [
            'correo_electronico' => 'test@example.com'
        ]);
    }

    public function test_no_puede_registrar_email_duplicado()
    {
        Usuario::create([
            'nombres' => 'Original',
            'apellidos' => 'User',
            'cedula' => '999888777',
            'nombre_usuario' => 'original',
            'correo_electronico' => 'duplicado@example.com',
            'telefono' => '099888777',
            'contrasenia' => bcrypt('password123'),
            'is_admin' => false
        ]);

        $response = $this->postJson('/api/registrar', [
            'nombres' => 'Copia',
            'apellidos' => 'User',
            'cedula' => '111222333',
            'nombre_usuario' => 'copia',
            'correo_electronico' => 'duplicado@example.com',
            'telefono' => '099111222',
            'contrasenia' => 'password123'
        ]);

        $response->assertStatus(422); 
    }

    public function test_solo_admin_puede_crear_categoria()
    {
        $usuarioNormal = Usuario::create([
            'nombres' => 'Normal', 'apellidos' => 'User', 'cedula' => '123', 
            'nombre_usuario' => 'normal', 'correo_electronico' => 'normal@test.com', 
            'telefono' => '123', 'contrasenia' => bcrypt('12345678'), 
            'is_admin' => false 
        ]);

        Sanctum::actingAs($usuarioNormal, ['*']);

        $response = $this->postJson('/api/categorias', [
            'categoria' => 'Hackeo'
        ]);

        $response->assertStatus(403);


        $admin = Usuario::create([
            'nombres' => 'Admin', 'apellidos' => 'Boss', 'cedula' => '999', 
            'nombre_usuario' => 'admin', 'correo_electronico' => 'admin@test.com', 
            'telefono' => '999', 'contrasenia' => bcrypt('12345678'), 
            'is_admin' => true 
        ]);

        Sanctum::actingAs($admin, ['*']);

        $responseAdmin = $this->postJson('/api/categorias', [
            'categoria' => 'Oficial'
        ]);

        $responseAdmin->assertStatus(201);
    }
}