<?php

namespace tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProductoControllerTest extends TestCase
{
    //use RefreshDatabase;  // Refrescar la base de datos después de cada prueba

    public function test_creando_multiples_productos(): void
    {
    //Loguearse

    $response = $this->post('/login', [
        'username' => 'admin',
        'password' => 'password', // Password as string, not enclosed in parenthesis.
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);

    // Crear 100 Productos
    Producto::factory()->count(100)->create();

    // Asegurarse que los productos se han creado correctamente
    $this->assertCount(100, Producto::all());
    }

}
