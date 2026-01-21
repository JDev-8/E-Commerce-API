<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Categoria;
use Laravel\Sanctum\Sanctum;

class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_agregar_items_al_carrito()
    {
        $categoria = Categoria::create(['categoria' => 'Electrónica']);
        
        $producto = Producto::create([
            'nombre' => 'iPhone 15',
            'stock' => 10,
            'precio' => 100000, 
            'categoria_id' => $categoria->id,
            'slug' => 'iphone-15'
        ]);

        $cliente = Usuario::create([
            'nombres' => 'Cliente', 'apellidos' => 'Feliz', 
            'cedula' => '999', 'nombre_usuario' => 'cliente', 
            'correo_electronico' => 'cliente@shop.com', 'telefono' => '555', 
            'contrasenia' => bcrypt('password'), 
            'is_admin' => false
        ]);

        Sanctum::actingAs($cliente, ['*']);

        $response = $this->postJson('/api/carrito', [
            'producto_id' => $producto->id,
            'cantidad' => 2
        ]);

        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Producto agregado al carrito']);

        $this->assertDatabaseHas('carrito_items', [
            'producto_id' => $producto->id,
            'cantidad' => 2
        ]);
    }

    public function test_no_puede_agregar_mas_del_stock_disponible()
    {
        $categoria = Categoria::create(['categoria' => 'Ropa']);
        $producto = Producto::create([
            'nombre' => 'Camisa Limitada',
            'stock' => 5,
            'precio' => 2000,
            'categoria_id' => $categoria->id,
            'slug' => 'camisa-limitada'
        ]);

        $cliente = Usuario::create([
            'nombres' => 'Comprador', 'apellidos' => 'Compulsivo', 
            'cedula' => '888', 'nombre_usuario' => 'buyer', 
            'correo_electronico' => 'buyer@shop.com', 'telefono' => '444', 
            'contrasenia' => bcrypt('password'),
            'is_admin' => false
        ]);

        Sanctum::actingAs($cliente, ['*']);

        $response = $this->postJson('/api/carrito', [
            'producto_id' => $producto->id,
            'cantidad' => 10 
        ]);

        $response->assertStatus(400)
                 ->assertJson(['mensaje' => 'No hay suficiente stock disponible.']);
    }
}